<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SKUSubmission;
use App\Models\SKUDetail;
use App\Notifications\SkuNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;

use App\Exports\SkuExport;
use Maatwebsite\Excel\Facades\Excel;

class SKUController extends Controller
{
    // Halaman List Pengajuan (Untuk Dept Head & FA)
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = SKUSubmission::with(['details', 'department', 'detail_department']);

        // LOGIC FILTER (Tambahkan ini agar sinkron dengan form filter di View)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->whereHas('details', function($q) use ($request) {
                $q->where('category', $request->category);
            });
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('issue_date', [$request->start_date, $request->end_date]);
        }

        // =========================================================================
        // [SLIDE/DOC REF] FILTER DATA BERDASARKAN ROLE / JABATAN (DATA SCOPING)
        // =========================================================================
        if ($user->hasRole('AdminSKU')) {
            // Admin PPIC: Bisa melihat seluruh pengajuan dari semua departemen
            $skus = $query->latest()->get(); 
        }
        // CEK FINANCE (Dept 7 + SPV)
        elseif ($user->dept_id == 7 && $user->position_id == 3) {
            // Finance (FA): Hanya melihat pengajuan yang statusnya >= 3 (Siap diinput SKU)
            $skus = $query->where('status', '>=', 3)->latest()->get();
        }
        // CEK DEPT HEAD / SPV (Selain PPIC)
        elseif ($user->position_id == 2) {
            // Department Head: Hanya melihat pengajuan dalam satu Departemennya
            $skus = $query->where('dept_id', $user->dept_id)->latest()->get();
        }
        elseif ($user->position_id == 3) {
            // Supervisor: Hanya melihat pengajuan dari sub-departemen / seksi miliknya
            $skus = $query->where('detail_dept_id', $user->detail_dept_id)->latest()->get();
        }
        // PIC BIASA
        else {
            // PIC / Staff Biasa: Hanya melihat pengajuan yang ia buat sendiri
            $skus = $query->where('npk', $user->npk)->latest()->get();
        }

        // Ambil daftar kategori unik dari tabel details, buang yang null
        $categories = SKUDetail::whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('sku.index', compact('skus', 'categories'));
    }

    // Halaman Form Input (PIC)
    public function create()
    {
        $user = auth()->user();

        if ($user->position_id == 2) {
            return redirect()->route('sku.index')->with('fail', 'Dept Head hanya diizinkan untuk approval, tidak bisa membuat pengajuan.');
        }
        return view('sku.create');
    }

    public function show($id)
    {
        $sku = SKUSubmission::with('details')->findOrFail($id);
        return view('sku.show', compact('sku'));
    }

    // Fungsi Simpan Data
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'details' => 'required|array|min:1',
            'details.*.item' => 'required',
            'details.*.qty' => 'required|numeric',
            'details.*.lampiran_foto' => 'nullable|file|mimes:jpg,pdf|max:2048',
            'issue_date' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            $user = auth()->user();

            // 2. Logic Status Awal (Foreman = 1, SPV = 2)
            // Jika user adalah SPV (posisi 2), status langsung 2 (nunggu Dept Head)
            $status = ($user->position_id == 3) ? 2 : 1;

            // 1. Generate prefix (SKU/PROD/2026/01/)
            $deptCode = strtoupper($user->department->code ?? 'UNK');
            $yearMonth = date('Y/m');
            $prefix = "SKU/{$deptCode}/{$yearMonth}/";

            // 2. Cari ID terakhir yang punya prefix SAMA di database
            $lastEntry = SKUSubmission::where('id_pengajuan', 'LIKE', $prefix . '%')
                            ->orderBy('id_pengajuan', 'desc')
                            ->first();

            if ($lastEntry) {
                // Ambil 4 angka terakhir dari string, misal '0002' jadi integer 2
                $lastNumber = intval(substr($lastEntry->id_pengajuan, -4));
                $newNumber = $lastNumber + 1;
            } else {
                // Kalau belum ada data sama sekali di bulan/tahun ini
                $newNumber = 1;
            }

            // 3. Gabungkan jadi ID Final
            $idPengajuan = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            // 4. SIMPAN HEADER (Cukup SEKALI saja)
            $submission = new SKUSubmission();
            $submission->id_pengajuan = $idPengajuan;
            $submission->nama = $user->name;
            $submission->npk = $user->npk;
            $submission->dept_id = $user->dept_id;
            $submission->detail_dept_id = $user->detail_dept_id;
            $submission->departement = $user->department->code;
            $submission->section = $request->section;
            $submission->remarks = $request->remarks;
            $submission->issue_date = date('Y-m-d', strtotime($request->issue_date));
            $submission->status = $status;
            $submission->save();

            // 5. LOOPING DETAIL (Simpan banyak item untuk SATU header di atas)
            foreach ($request->details as $index => $row) {
                $detail = new SKUDetail();
                $detail->sku_submission_id = $submission->id; // Hubungkan ke ID Header
                $detail->item_name = $row['item'];
                $detail->specification = $row['spec'];
                $detail->product_code = $row['product_code'] ?? null;
                $detail->sku = $row['sku'] ?? null;
                $detail->qty = $row['qty'];
                $detail->uom = $row['uom'];
                $detail->category = $row['category'] ?? null;
                $detail->usage = $row['usage'] ?? 0;
                $detail->keperluan = $row['keperluan'] ?? null;
                $detail->due_date = $row['due_date'];

                // Upload Foto khusus untuk item ini
                if ($request->hasFile("details.$index.lampiran_foto")) {
                    $file = $request->file("details.$index.lampiran_foto");
                    $filename = $submission->id . '_' . $index . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('uploads/sku', $filename, 'public');
                    $detail->lampiran_foto = $path;
                }

                $detail->save();
            }

                // 6. NOTIFIKASI DINAMIS (Fix: SPV satu seksi dapet notif)
                $notifRecipient = null;
                $notifTitle = '';

                // Jika User adalah SPV (Posisi 3), kirim ke Dept Head (Posisi 2)
                if ($user->position_id == 3) {
                    $notifRecipient = User::where('dept_id', $user->dept_id)
                                        ->where('position_id', 2)
                                        ->first();
                    $notifTitle = 'New SKU Submission (Need Approval)';
                } 
                // Jika User adalah Foreman/Staff (Bukan SPV), kirim ke SPV (Posisi 3) satu SEKSI
                else {
                    $notifRecipient = User::where('detail_dept_id', $user->detail_dept_id)
                                        ->where('position_id', 3)
                                        ->first();
                    $notifTitle = 'New SKU Submission (Waiting SPV)';
                }

                // Eksekusi Kirim Notif
                $this->safeNotify($notifRecipient, [
                    'title'   => $notifTitle,
                    'message' => 'New request ' . $idPengajuan . ' from ' . $user->name,
                    'sku_id'  => $submission->id,
                    'icon'    => 'fa-file-text',
                    'color'   => 'text-info'
                ]);

            DB::commit();
            return redirect()->route('sku.index')->with('success', "Pengajuan $idPengajuan berhasil dibuat!");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('fail', 'Gagal simpan data: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // [SLIDE/DOC REF] ALUR PERSETUJUAN BERJENJANG (APPROVAL WORKFLOW LOGIC)
    // =========================================================================
    public function approve($id)
    {
        $sku = SKUSubmission::findOrFail($id);
        $user = auth()->user();
        $nextStatus = $sku->status;
        
        $notifToNext = null; // Untuk Approver selanjutnya
        $notifNextData = [];
        
        $requestor = User::where('npk', $sku->npk)->first(); // Pembuat asli (PIC)
        $msgRequestor = "";

        DB::beginTransaction();
        try {
            // 1. SUPERVISOR APPROVE (Status 1 -> 2)
            if ($sku->status == 1 && $user->position_id == 3 && $user->detail_dept_id == $sku->detail_dept_id) {
                $nextStatus = 2;
                
                // Notif untuk DEPT HEAD (Level selanjutnya)
                $notifToNext = User::where('dept_id', $sku->dept_id)->where('position_id', 2)->first();
                $notifNextData = [
                    'title'   => 'Waiting Dept Head Approval',
                    'message' => 'SKU request ' . $sku->id_pengajuan . ' approved by SPV. Needs your approval.',
                    'sku_id'  => $sku->id,
                    'icon'    => 'fa-chevron-circle-up',
                    'color'   => 'text-success'
                ];

                // Pesan untuk Requestor (Foreman)
                $msgRequestor = "Your request " . $sku->id_pengajuan . " has been approved by Supervisor and moved to Dept Head.";
            } 
            
            // 2. DEPT HEAD APPROVE (Status 2 -> 3)
            elseif ($sku->status == 2 && $user->position_id == 2 && $user->dept_id == $sku->dept_id) {
                $nextStatus = 3;
                
                // Notif untuk SEMUA tim FINANCE (Dept 7)
                $financeUsers = User::where('dept_id', 7)->where('position_id', 3)->get();
                foreach ($financeUsers as $f) {
                    $this->safeNotify($f, [
                        'title'   => 'New SKU to Process',
                        'message' => 'Request ' . $sku->id_pengajuan . ' is ready for SKU & Part No input.',
                        'sku_id'  => $sku->id,
                        'icon'    => 'fa-money',
                        'color'   => 'text-primary'
                    ]);
                }

                // Pesan untuk Requestor (Foreman/SPV)
                $msgRequestor = "Your request " . $sku->id_pengajuan . " has been approved by Dept Head. Finance will process the SKU soon.";
            }

            // 3. PPIC / FINAL VALIDATOR (Status 5 -> 6)
            elseif ($sku->status == 5 && $user->hasRole('AdminSKU')) {
                $nextStatus = 6;

                // --- LOGIC COPY KE MASTER PRODUCT ---
                foreach ($sku->details as $detail) {
    
                    $finalImage = null; // Default kosong (untuk PDF)

                    // 1. Cek apakah di database ada path-nya
                    if ($detail->lampiran_foto) {
                        
                        // 2. CEK FISIK: Pastikan file beneran ada di storage
                        if (Storage::disk('public')->exists($detail->lampiran_foto)) {
                            
                            $extension = strtolower(pathinfo($detail->lampiran_foto, PATHINFO_EXTENSION));
                            
                            // 3. FILTER: Hanya proses jika file adalah GAMBAR
                            if (in_array($extension, ['jpg'])) {
                                
                                // --- [UPDATE FORMAT NAMA FILE] ---
                                // Format: KODE-BARANG_TIMESTAMP.EXT
                                // (Sama persis dengan ProductController)
                                
                                // 1. Bersihkan kode barang (Ganti / dan spasi jadi -)
                                $cleanCode = str_replace(['/', '\\', ' '], '-', $detail->product_code);
                                
                                // 2. Susun Nama File Baru
                                // Menggunakan time() agar unik dan seragam
                                $newFileName = $cleanCode . '_' . time() . '.' . $extension;
                                
                                // 3. Tentukan Path Tujuan
                                $newPath = 'products/' . $newFileName;

                                // 4. COPY file dari 'uploads/sku' ke 'products'
                                Storage::disk('public')->copy($detail->lampiran_foto, $newPath);
                                
                                $finalImage = $newPath;
                            }
                        }
                    }

                    // Update atau Create ke Master Product
                    \App\Models\Product::updateOrCreate(
                        ['product_code' => $detail->product_code], 
                        [
                            'sku_code'          => $detail->sku,
                            'item_name'         => $detail->item_name,
                            'specification'     => $detail->specification,
                            'uom'               => $detail->uom,
                            'category'          => $detail->category,
                            'product_image'     => $finalImage, 
                            'input_source'      => 'submission',
                            'sku_submission_id' => $sku->id,
                            'usage_month'       => $detail->usage
                        ]
                    );
                }
                
                // Pesan penutup untuk Requestor
                $msgRequestor = "Congratulations! Your request " . $sku->id_pengajuan . " has been fully validated by PPIC and added to Master Product.";
            }

            // Update status di database
            $sku->update(['status' => $nextStatus]);

            // KIRIM NOTIF KE APPROVER SELANJUTNYA (Jika ada)
            if ($notifToNext && !empty($notifNextData)) {
                $this->safeNotify($notifToNext, $notifNextData);
            }

            // KIRIM NOTIF KE REQUESTOR (Update Progres)
            if ($requestor && $msgRequestor != "") {
                $this->safeNotify($requestor, [
                    'title'   => 'SKU Submission Update',
                    'message' => $msgRequestor,
                    'sku_id'  => $sku->id,
                    'icon'    => ($nextStatus == 6) ? 'fa-check-double' : 'fa-info-circle',
                    'color'   => ($nextStatus == 6) ? 'text-navy' : 'text-info'
                ]);
            }

            DB::commit();
            return back()->with('success', 'Approval successful and notifications have been sent!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('fail', 'Error: ' . $e->getMessage());
        }
    }

    public function checkConflicts($id)
    {
        $sku = SKUSubmission::with('details')->findOrFail($id);
        $conflicts = [];
        $hasConflict = false;

        foreach ($sku->details as $detail) {
            // 1. Cek Part Number
            if ($detail->product_code) {
                $existingProduct = \App\Models\Product::where('product_code', $detail->product_code)->first();
                
                if ($existingProduct) {
                    $hasConflict = true;
                    $conflicts[] = [
                        'type' => 'Part Number Duplicate',
                        // --- DESIGN BARU: Kiri (Kode) - Kanan (Nama Barang) ---
                        'message' => '
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;">
                                <div style="text-align: left; width: 45%;">
                                    <small class="text-muted" style="display:block; font-size: 10px;">Duplicate Part No:</small>
                                    <span style="background-color: #f8ac59; color: white; padding: 2px 6px; border-radius: 4px; font-weight: bold; font-size: 12px;">' . $detail->product_code . '</span>
                                </div>
                                <div style="color: #ccc; font-size: 14px;">
                                    <i class="fa fa-arrow-right"></i>
                                </div>
                                <div style="text-align: right; width: 45%;">
                                    <small class="text-muted" style="display:block; font-size: 10px;">Used by Item:</small>
                                    <strong style="color: #555; font-size: 12px; display: block; line-height: 1.2;">' . $existingProduct->item_name . '</strong>
                                </div>
                            </div>'
                    ];
                }
            }

            // 2. Cek SKU
            if ($detail->sku) {
                $existingSku = \App\Models\Product::where('sku_code', $detail->sku)
                                ->where('product_code', '!=', $detail->product_code)
                                ->first();

                if ($existingSku) {
                    $hasConflict = true;
                    $conflicts[] = [
                        'type' => 'SKU Duplicate',
                        'message' => '
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;">
                                <div style="text-align: left; width: 45%;">
                                    <small class="text-muted" style="display:block; font-size: 10px;">Duplicate SKU:</small>
                                    <span style="background-color: #ed5565; color: white; padding: 2px 6px; border-radius: 4px; font-weight: bold; font-size: 12px;">' . $detail->sku . '</span>
                                </div>
                                <div style="color: #ccc; font-size: 14px;">
                                    <i class="fa fa-arrow-right"></i>
                                </div>
                                <div style="text-align: right; width: 45%;">
                                    <small class="text-muted" style="display:block; font-size: 10px;">Used by Item:</small>
                                    <strong style="color: #555; font-size: 12px; display: block; line-height: 1.2;">' . $existingSku->item_name . '</strong>
                                </div>
                            </div>'
                    ];
                }
            }
        }
        
        // ... (return response sama seperti sebelumnya) ...
        return response()->json([
            'status' => 'success',
            'has_conflict' => $hasConflict,
            'conflicts' => $conflicts
        ]);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reject_reason' => 'required|min:5'
        ]);

        $sku = SKUSubmission::findOrFail($id);
        $user = auth()->user();

        DB::beginTransaction();
        try {
            // Default: Rejected with Revision (Status 4)
            $statusTarget = 4;
            $titleNotif = 'Submission Rejected (Revision Needed)';
            $icon = 'fa-edit';
            $color = 'text-warning';
            $msgRedirect = 'Rejected for Revision';

            // KHUSUS PPIC (Final Validation): Reject Permanen (Status 7)
            if ($sku->status == 5 && $user->hasRole('AdminSKU')) {
                $statusTarget = 7; 
                $titleNotif = 'Submission PERMANENTLY Rejected';
                $icon = 'fa-ban';
                $color = 'text-danger';
                $msgRedirect = 'Permanently Rejected';
            }

            // Update Data di Database
            $sku->update([
                'status' => $statusTarget,
                'reject_reason' => $request->reject_reason
            ]);

            // Kirim Notifikasi ke Requestor (PIC Asli)
            $pic = User::where('npk', $sku->npk)->first();
            $this->safeNotify($pic, [
                'title'   => $titleNotif,
                'message' => 'Reason: ' . $request->reject_reason . ' (Rejected by ' . $user->name . ')',
                'sku_id'  => $sku->id,
                'icon'    => $icon,
                'color'   => $color
            ]);

            DB::commit();
            return redirect()->route('sku.index')->with('success', 'Submission #' . $sku->id_pengajuan . ' processed as: ' . $msgRedirect);
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('fail', 'Error: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // [SLIDE/DOC REF] INPUT SKU DAN PART NUMBER OLEH FINANCE (FA UPDATE)
    // =========================================================================
    public function updateByFA(Request $request, $id)
    {
        // PROTEKSI: Hanya boleh diakses oleh Dept Finance (Dept ID 7 & Position ID 3 / Supervisor)
        $user = auth()->user();
        if ($user->dept_id != 7 || $user->position_id != 3) {
            abort(403, 'Access Denied.');
        }

        DB::beginTransaction();

        try {
            // 2. Update Detail Barang satu per satu
            // Kita loop array 'details' yang dikirim dari form Blade lo
            if ($request->has('details')) {
                foreach ($request->details as $detailId => $inputData) {
                    \App\Models\SKUDetail::where('id', $detailId)->update([
                        'product_code' => $inputData['product_code'],
                        'sku'          => $inputData['sku']
                    ]);
                }
            }

            // 3. Update status header submission jadi 4 (Completed)
            $sku = SKUSubmission::findOrFail($id);
            $sku->update(['status' => 5]);

            // [NOTIFIKASI] Kirim ke PPIC Admin (Dept 9, Pos 3)
            $ppicUsers = User::where('dept_id', 9)->where('position_id', 3)->get(); // Pakai get() biar semua dapet
            foreach ($ppicUsers as $ppic) {
                $this->safeNotify($ppic, [
                    'title'   => 'Finance Updated SKU',
                    'message' => 'SKU numbers have been filled for ' . $sku->nama . '. Please perform Final ACC.',
                    'sku_id'  => $sku->id,
                    'icon'    => 'fa-check-square-o',
                    'color'   => 'text-info'
                ]);
            }

            // [NOTIFIKASI] Kirim ke user PIC (Requestor)
            $pic = User::where('npk', $sku->npk)->first();
            $this->safeNotify($pic, [
                'title'   => 'SKU Numbers Assigned',
                'message' => 'Finance has assigned Part No & SKU. Waiting for final validation from PPIC.',
                'sku_id'  => $sku->id,
                'icon'    => 'fa-barcode',
                'color'   => 'text-info'
            ]);

            DB::commit();
            return redirect()->route('sku.index')->with('success', 'SKU Updated. Waiting for Final Validation from PPIC.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('fail', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $sku = SKUSubmission::with('details')->findOrFail($id);

        // 2. Ambil data dropdown dari Model SKUDetail
        $categories = \App\Models\SKUDetail::getCategories();
        $uoms = \App\Models\SKUDetail::getUoms();

        // Proteksi: Hanya pemilik data (Requestor) yang bisa edit 
        // DAN statusnya harus REJECTED (Status 4)
        if ($sku->npk != auth()->user()->npk || $sku->status != 4) {
            return redirect()->route('sku.index')->with('fail', 'Access denied: You can only edit rejected requests that you submitted.');
        }
        if ($sku->status == 7) {
        return redirect()->route('sku.index')->with('fail', 'This submission has been permanently rejected and cannot be edited.');
        }

        return view('sku.edit', compact('sku', 'categories', 'uoms'));
    }

    public function update(Request $request, $id)
    {
        $sku = SKUSubmission::findOrFail($id);
        $user = auth()->user();

        $newStatus = ($user->position_id == 3) ? 2 : 1;

        DB::beginTransaction();
        try {
            // 1. Update Header
            $sku->update([
                'status'        => $newStatus, 
                'reject_reason' => null, // Reset alasan reject biar bersih
                'remarks'       => $request->remarks,
                'issue_date'    => now()->format('Y-m-d'),
            ]);

            foreach ($request->details as $index => $row) {
                // Cari detail yang sudah ada
                $detail = SKUDetail::where('id', $row['id'])->first();

                if ($detail) {
                    $data = [
                        'item_name' => $row['item_name'],
                        'specification' => $row['specification'],
                        'qty' => $row['qty'],
                        'usage' => $row['usage'],
                        'keperluan' => $row['keperluan'],
                        'category'      => $row['category'],
                        'uom'           => $row['uom'],
                    ];

                    // Jika user upload foto baru
                    if ($request->hasFile("details.$index.photo")) {
                        $file = $request->file("details.$index.photo");
                        $data['lampiran_foto'] = $file->store('uploads/sku', 'public');
                    }

                    $detail->update($data);
                }
            }
            // 3. NOTIFIKASI DINAMIS
            $notifTo = null;
            if ($newStatus == 1) {
                // Jika balik ke SPV, cari SPV di seksi yang sama
                $notifTo = User::where('detail_dept_id', $sku->detail_dept_id)
                            ->where('position_id', 3)
                            ->first();
            } else {
                // Jika balik ke Dept Head, cari Dept Head di departemen yang sama
                $notifTo = User::where('dept_id', $sku->dept_id)
                            ->where('position_id', 2)
                            ->first();
            }

            if ($notifTo) {
                $this->safeNotify($notifTo, [
                    'title'   => 'Revision Resubmitted',
                    'message' => $user->name . ' has fixed the request ' . $sku->id_pengajuan,
                    'sku_id'  => $sku->id,
                    'icon'    => 'fa-refresh',
                    'color'   => 'text-warning'
                ]);
            }

            DB::commit();
            return redirect()->route('sku.index')->with('success', 'Revision has been resubmitted.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('fail', $e->getMessage());
        }
    }

    public function dashboard()
    {
        $user = auth()->user();
        $statQuery = SKUSubmission::query();
        $activityQuery = SKUSubmission::with('details');

        // 1. Logic Scope Data (Siapa melihat apa)
        if ($user->hasRole('AdminSKU') || $user->dept_id == 7) {
            // PPIC Admin & Finance (FA) bisa melihat semua data untuk monitoring
        } elseif ($user->position_id == 2) {
            // Dept Head melihat data dalam satu Departemen
            $statQuery->where('dept_id', $user->dept_id);
            $activityQuery->where('dept_id', $user->dept_id);
        } elseif ($user->position_id == 3) {
            // Supervisor melihat data dalam satu Section (Detail Dept)
            $statQuery->where('detail_dept_id', $user->detail_dept_id);
            $activityQuery->where('detail_dept_id', $user->detail_dept_id);
        } else {
            // PIC / Foreman cuma bisa lihat buatannya sendiri
            $statQuery->where('npk', $user->npk);
            $activityQuery->where('npk', $user->npk);
        }

        // 2. Hitung Statistik berdasarkan status yang baru
        $stats = [
            'total_all'    => (clone $statQuery)->count(),
            'pending_spv'  => (clone $statQuery)->where('status', 1)->count(), // Waiting SPV
            'pending_head' => (clone $statQuery)->where('status', 2)->count(), // Waiting Dept Head
            'waiting_fa'   => (clone $statQuery)->where('status', 3)->count(), // Waiting Finance Input
            'rejected'     => (clone $statQuery)->where('status', 4)->count(), // Rejected (Needs Revision)
            'waiting_maya' => (clone $statQuery)->where('status', 5)->count(), // Waiting PPIC Validation
            'completed'    => (clone $statQuery)->where('status', 6)->count(), // Finished
            'final_rejected' => (clone $statQuery)->where('status', 7)->count(), // Final Rejected
        ];

        // Ambil 5 aktivitas terbaru
        $recentSubmissions = $activityQuery->latest()->take(5)->get();

        return view('sku.dashboard', compact('stats', 'recentSubmissions'));
    }

    public function export(Request $request) 
    {
        $user =auth()->user();
        // Proteksi: Cek apakah user produksi biasa
        if ($user->position_id == 2) {
            return redirect()->back()->with('fail', 'Access Denied: You do not have permission to export data.');
        }
        return Excel::download(new SkuExport($request), 'SKU_Report_'.date('Ymd').'.xlsx');
    }

    public function searchMasterProducts(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        // Cari di master product table. Pastikan statusnya 'active'!
        $products = \App\Models\Product::where('status', 'active')
            ->where(function($query) use ($q) {
                $query->where('item_name', 'LIKE', "%{$q}%")
                      ->orWhere('product_code', 'LIKE', "%{$q}%");
            })
            ->limit(10)
            ->get(['item_name', 'product_code', 'specification', 'uom', 'category']);

        return response()->json($products);
    }

    // =========================================================================
    // [SLIDE/DOC REF] OPTIMASI PENGIRIMAN EMAIL ASINKRON (NON-BLOCKING afterResponse)
    // =========================================================================
    private function safeNotify($recipient, array $details)
    {
        try {
            if ($recipient) {
                dispatch(function () use ($recipient, $details) {
                    try {
                        $recipient->notifyNow(new SkuNotification($details));
                    } catch (\Throwable $e) {
                        \Log::error('Deferred SkuNotification failed: ' . $e->getMessage(), [
                            'recipient_id' => $recipient ? $recipient->id : 'unknown',
                            'recipient_email' => $recipient ? $recipient->email : 'unknown',
                            'details' => $details
                        ]);
                    }
                })->afterResponse();
            }
        } catch (\Throwable $e) {
            \Log::error('Failed to dispatch deferred notification: ' . $e->getMessage(), [
                'recipient_id' => $recipient ? $recipient->id : 'unknown',
                'recipient_email' => $recipient ? $recipient->email : 'unknown',
                'details' => $details,
                'exception' => $e
            ]);
        }
    }
}
