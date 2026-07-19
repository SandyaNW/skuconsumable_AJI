<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Imports\ProductImport;
use App\Exports\ProductExport;
use App\Exports\ProductTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function __construct()
    {
        // 1. Izinkan AdminSKU atau Supervisor (position_id == 3) untuk mengakses controller ini
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if ($user && ($user->hasRole('AdminSKU') || $user->position_id == 3)) {
                return $next($request);
            }
            abort(403, 'Unauthorized action.');
        });

        // 2. Proteksi Aksi Modifikasi: Hanya AdminSKU yang bisa mengubah/mengimpor data
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if ($user && $user->hasRole('AdminSKU')) {
                return $next($request);
            }
            abort(403, 'Only PPIC Admin is authorized to modify master product data.');
        })->except(['index', 'export']);
    }

    public function index(Request $request)
    {
        $query = Product::query();
        
        // Filter Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('item_name', 'LIKE', "%{$request->search}%")
                ->orWhere('product_code', 'LIKE', "%{$request->search}%")
                ->orWhere('sku_code', 'LIKE', "%{$request->search}%");
            });
        }

        // Filter Category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Sorting Logic
        $sortColumn = $request->get('sort', 'created_at'); // default sort
        $direction = $request->get('direction', 'desc');   // default direction
        
        $products = $query->orderBy($sortColumn, $direction)->paginate(20);

        $products = $query->latest()->paginate(20);
        return view('sku.products.index', compact('products'));
    }

    public function create()
    {
        return view('sku.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_code' => 'required|unique:products,product_code|max:100',
            'sku_code'     => 'nullable|max:100',
            'item_name'    => 'required|max:255',
            'uom'          => 'required|max:20',
            'category'     => 'required',
            'usage_month'  => 'nullable|numeric',
            'moq'          => 'nullable|numeric',
            'lot'          => 'nullable|numeric',
            'min'          => 'nullable|numeric',
            'rop'          => 'nullable|numeric',
            'max'          => 'nullable|numeric',
            'status'       => 'required|in:active,inactive',
        ]);

        Product::create([
            'product_code'  => strtoupper(trim($request->product_code)),
            'sku_code'      => $request->sku_code ? strtoupper(trim($request->sku_code)) : '-',
            'item_name'     => $request->item_name,
            'specification' => $request->specification,
            'uom'           => strtoupper($request->uom),
            'category'      => $request->category,
            'input_source'  => 'submission', // Tandai sebagai input manual
            'usage_month'   => $request->usage_month,
            'moq'           => $request->moq,
            'lot'           => $request->lot,
            'min'           => $request->min,
            'rop'           => $request->rop,
            'max'           => $request->max,
            'status'        => $request->status,
        ]);

        return redirect()->route('products.index')->with('success', 'Product successfully added manually.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('sku.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
    
        $request->validate([
            'product_code' => 'required|unique:products,product_code,' . $id,
            'item_name'    => 'required',
            'product_image'=> 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'usage_month'  => 'nullable|numeric',
            'moq'          => 'nullable|numeric',
            'lot'          => 'nullable|numeric',
            'min'          => 'nullable|numeric',
            'rop'          => 'nullable|numeric',
            'max'          => 'nullable|numeric',
            'status'       => 'required|in:active,inactive',
        ]);

            // 1. Siapkan array data dasar (tanpa gambar dulu)
            $data = $request->except(['product_image']); 

            // 2. LOGIC UPLOAD GAMBAR BARU
            if ($request->hasFile('product_image')) {
                
                // Hapus gambar lama jika ada
            if ($product->product_image && Storage::disk('public')->exists($product->product_image)) {
                Storage::disk('public')->delete($product->product_image);
            }

            // Simpan gambar baru
            $file = $request->file('product_image');

            // --- BERSIHKAN NAMA FILE (Sanitasi) ---
            // Ganti garis miring (/) atau spasi jadi strip (-) biar aman
            // Contoh: "BOX/CARD/001" jadi "BOX-CARD-001"
            $cleanCode = str_replace(['/', '\\', ' '], '-', $product->product_code);
            
            // Format Baru: KODE-BARANG_TIMESTAMP.EXT
            // Contoh: BOX-CARD-001_17672399.jpg
            $filename = $cleanCode . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Simpan
            $path = $file->storeAs('products', $filename, 'public');
            
            // Masukkan path string ke array data
            $data['product_image'] = $path; 
        }

        // 3. UPDATE DATABASE DENGAN ARRAY $DATA
        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product data successfully updated.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product successfully deleted.');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        
        try {
            Excel::import(new ProductImport, $request->file('file'));
            return back()->with('success', 'Master Data Berhasil di-Import!');
        } catch (\Exception $e) {
            return back()->with('fail', 'Import failed: ' . $e->getMessage());
        }
    }   

    public function previewImport(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

        try {
            // Kita baca Excel pakai aturan yang sudah diset di ProductImport tadi
            $rows = Excel::toCollection(new ProductImport, $request->file('file'))->first(); 
            
            $conflicts = [];
            $newCount = 0;
            
            foreach ($rows as $index => $row) {
                // Sekarang kita panggil nama pastinya saja (sesuai excel kamu)
                // Laravel otomatis ubah "PART NUMBER" jadi "part_number"
                $code = strtoupper(trim($row['part_number'] ?? $row['product_code'] ?? '')); 
                $name = $row['part_name'] ?? $row['item_name'] ?? 'Unknown Item';

                // Skip jika kosong
                if (!$code) continue;

                $existing = Product::where('product_code', $code)->first();
                
                if ($existing) {
                    $conflicts[] = [
                        'row' => $index + 3, // +3 (Karena Header di baris 2 + Index Array mulai 0)
                        'code' => $code,
                        'new_name' => $name,
                        'old_name' => $existing->item_name
                    ];
                } else {
                    $newCount++;
                }
            }

            $path = $request->file('file')->store('temp_imports', 'local');

            return response()->json([
                'status' => 'success',
                'total_rows' => count($rows),
                'new_count' => $newCount,
                'conflict_count' => count($conflicts),
                'conflicts' => $conflicts,
                'temp_path' => $path
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function processImport(Request $request)
    {
        $tempPath = $request->temp_path;
        $action = $request->action;

        if (!Storage::disk('local')->exists($tempPath)) {
            return response()->json(['status' => 'error', 'message' => 'File expired.']);
        }

        try {
            $rows = Excel::toCollection(new ProductImport, Storage::disk('local')->path($tempPath))->first();
            
            $stats = ['processed' => 0, 'updated' => 0, 'skipped' => 0];

            foreach ($rows as $row) {
                // Panggil key yang pasti-pasti saja
                $code = strtoupper(trim($row['part_number'] ?? $row['product_code'] ?? ''));
                
                if (!$code) continue;

                // Mapping Data Sesuai Kolom Excel Kamu
                $data = [
                    'product_code' => $code,
                    'item_name'    => $row['part_name'] ?? $row['item_name'] ?? 'Unknown',
                    'sku_code'     => $row['sku'] ?? '-', // Di Excel judulnya "SKU", jadi keynya 'sku'
                    'uom'          => strtoupper($row['uom'] ?? 'UNIT'),
                    'category'     => $row['category'] ?? 'GENERAL',
                    'input_source' => 'existing',
                    'usage_month'  => $row['usage_month'] ?? null,
                    'moq'          => $row['moq'] ?? null,
                    'lot'          => $row['lot'] ?? null,
                    'min'          => $row['min'] ?? null,
                    'rop'          => $row['rop'] ?? null,
                    'max'          => $row['max'] ?? null,
                    'status'       => isset($row['status']) ? strtolower(trim($row['status'])) : 'active',
                ];

                $existing = Product::where('product_code', $code)->first();

                if ($existing) {
                    if ($action == 'overwrite') {
                        $existing->update($data);
                        $stats['updated']++;
                    } else {
                        $stats['skipped']++;
                    }
                } else {
                    Product::create($data);
                    $stats['processed']++;
                }
            }

            Storage::disk('local')->delete($tempPath);
            
            return response()->json([
                'status' => 'success', 
                'message' => "Done! New: {$stats['processed']}, Updated: {$stats['updated']}, Skipped: {$stats['skipped']}"
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function export()
    {
        $fileName = 'Master_Product_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new ProductExport, $fileName);
    }

    public function downloadTemplate()
    {
        return Excel::download(new ProductTemplateExport, 'Template_Import_Master_Product.xlsx');
    }
}
