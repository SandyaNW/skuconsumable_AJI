@extends('layouts.app-master')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    {{-- Back Button --}}
    <div class="row mb-3">
        <div class="col-lg-12">
            <a href="{{ route('sku.index') }}" class="btn btn-white"><i class="fa fa-arrow-left"></i> Back to List</a>
        </div>
    </div>

    {{-- Revision Alert --}}
    @if($sku->status == 4 && auth()->user()->npk == $sku->npk)
        <div class="ibox shadow-sm m-t-sm">
            <div class="ibox-content p-md" style="border-left: 5px solid #a94442; color: #a94442; background-color: #f2dede;">
                <div class="row">
                    <div class="col-md-9">
                        <h3 class="m-t-none text-danger"><i class="fa fa-exclamation-circle"></i> REVISION REQUIRED</h3>
                        <p class="m-b-none">Rejection Reason: <strong>"{{ $sku->reject_reason ?? 'No specific reason provided.' }}"</strong></p>
                        <small>*Please update the information below and resubmit your request.</small>
                    </div>
                    <div class="col-md-3 text-right">
                        <a href="{{ route('sku.edit', $sku->id) }}" class="btn btn-danger btn-outline btn-lg font-bold" style="background: white;">
                            <i class="fa fa-edit"></i> REVISE NOW
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($sku->status == 7)
        <div class="ibox shadow-sm m-t-sm">
            <div class="ibox-content p-md" style="border-left: 5px solid #000; color: #fff; background-color: #333;">
                <h3 class="m-t-none text-white"><i class="fa fa-ban"></i> SUBMISSION CLOSED</h3>
                <p class="m-b-none">Reason for Permanent Rejection: <strong>"{{ $sku->reject_reason }}"</strong></p>
                <small>*This request cannot be revised. Please create a new submission if necessary.</small>
            </div>
        </div>
    @endif

    {{-- Box 1: Submission Information --}}
    <div class="ibox shadow-sm">
        <div class="ibox-title" style="border-top: 4px solid #1ab394;">
            <h5><i class="fa fa-info-circle text-navy"></i> Submission Information</h5>
            <div class="ibox-tools">
                <span class="label label-primary" style="font-size: 12px;">
                    <i class="fa fa-tag"></i> {{ $sku->id_pengajuan ?? 'SKU-'.str_pad($sku->id, 5, '0', STR_PAD_LEFT) }}
                </span>
            </div>
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-stripped">
                        <tr>
                            <td width="35%"><strong>Requestor Name</strong></td>
                            <td>: {{ $sku->nama }}</td>
                        </tr>
                        <tr>
                            <td><strong>NPK</strong></td>
                            <td>: {{ $sku->npk }}</td>
                        </tr>
                        <tr>
                            <td><strong>Department</strong></td>
                            <td>: {{ $sku->departement }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-stripped">
                        <tr>
                            <td><strong>Section</strong></td>
                            <td>: {{ $sku->detail_department->name ?? $sku->section ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td width="35%"><strong>Issue Date</strong></td>
                            <td>: {{ date('d-M-Y', strtotime($sku->issue_date)) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Current Status</strong></td>
                            <td>: 
                                @if($sku->status == 1)
                                    <span class="label label-warning"><i class="fa fa-clock-o"></i> Waiting Supervisor Approval </span>
                                @elseif($sku->status == 2)
                                    <span class="label label-info"><i class="fa fa-pencil"></i> Waiting Dept Head Approval</span>
                                @elseif($sku->status == 3)
                                    <span class="label label-info"><i class="fa fa-pencil"></i> Processing by Finance</span>
                                @elseif($sku->status == 4)
                                    <span class="label label-danger"><i class="fa fa-times"></i> Rejected</span>
                                @elseif($sku->status == 5)
                                    <span class="label label-primary" style="background-color: #23c6c8;"><i class="fa fa-eye"></i> Waiting PPIC Validation</span>
                                @elseif($sku->status == 6)
                                    <span class="label label-primary" style="background-color: #1ab394;"><i class="fa fa-check"></i> Completed</span>
                                @elseif($sku->status == 7)
                                    <span class="label label-danger" style="background-color: #000;"><i class="fa fa-ban"></i> Permanently Rejected</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($sku->remarks)
        <div class="ibox shadow-sm m-t-md animated fadeInDown">
            <div class="ibox-title" style="background-color: #fafafa; border-left: 4px solid #f8ac59;">
                <h5><i class="fa fa-commenting text-warning"></i> Remarks (Notes from Requestor)</h5>
            </div>
            <div class="ibox-content" style="background-color: #fffdf5;">
                <div class="well well-sm mb-0" style="background: white; border: 1px dashed #f8ac59;">
                    <p class="m-b-none" style="font-size: 14px; color: #676a6c;">
                        <i class="fa fa-quote-left text-muted"></i> 
                        <strong>{{ $sku->remarks }}</strong> 
                        <i class="fa fa-quote-right text-muted"></i>
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Box 2: Item List --}}
    <div class="ibox shadow-sm m-t-md">
        <div class="ibox-title">
            <h5><i class="fa fa-shopping-cart text-navy"></i> Item List & Verification</h5>
        </div>
        <div class="ibox-content">
            <form id="formFinance" action="{{ route('sku.update_fa', $sku->id) }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" style="min-width: 1200px;">
                        <thead>
                            <tr class="bg-light">
                                <th width="30">No</th>
                                <th>Item Name</th>
                                <th>Specification</th>
                                <th>Qty</th>
                                <th>UOM</th>
                                <th>Part No (Finance Section)</th>
                                <th>SKU (Finance Section)</th>
                                <th>Category</th>
                                <th>Usage/Month</th>
                                <th class="text-center">Attachment</th>
                                <th>Purpose</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sku->details as $index => $detail)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><strong>{{ $detail->item_name }}</strong></td>
                                <td><small>{{ $detail->specification ?? '-' }}</small></td>
                                <td class="text-center">{{ $detail->qty }}</td>
                                <td>{{ $detail->uom }}</td>
                                <td>
                                    @if(auth()->user()->dept_id == 7 && $sku->status == 3)
                                        <div class="form-group m-b-xs">
                                            <input type="text" name="details[{{ $detail->id }}][product_code]" class="form-control input-sm" placeholder="Part No..." required>
                                        </div>
                                    @else
                                        <strong>PN:</strong> {{ $detail->product_code ?? '-' }}<br>
                                    @endif
                                </td>
                                <td>
                                    @if(auth()->user()->dept_id == 7 && $sku->status == 3)
                                        <div class="form-group mb-0">
                                            <input type="text" name="details[{{ $detail->id }}][sku]" class="form-control input-sm" placeholder="SKU Number..." required>
                                        </div>
                                    @else
                                        <strong>SKU:</strong> {{ $detail->sku ?? '-' }}
                                    @endif
                                </td>
                                <td >{{ $detail->category }}</td>
                                <td class="text-center">{{ $detail->usage }}</td>
                                <td class="text-center" style="vertical-align: middle;">
                                    @if($detail->lampiran_foto)
                                        @php
                                            $filePath = asset('storage/' . $detail->lampiran_foto);
                                            $extension = strtolower(pathinfo($detail->lampiran_foto, PATHINFO_EXTENSION));
                                        @endphp

                                        <div class="m-b-xs">
                                        @if(in_array($extension, ['jpg']))
                                            <img src="{{ $filePath }}" 
                                                class="img-thumbnail" 
                                                style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                                                onclick="showImage('{{ $filePath }}', '{{ $detail->item_name }}')">
                                        @elseif($extension == 'pdf')
                                            <button type="button" class="btn btn-danger btn-circle" onclick="showPdf('{{ $filePath }}')">
                                                <i class="fa fa-file-pdf-o"></i>
                                            </button>
                                        @endif
                                        </div>
                                        <a href="{{ $filePath }}" download class="btn btn-xs btn-white btn-block" style="font-size: 10px;">
                                            <i class="fa fa-download"></i> Download
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td><small>{{ $detail->keperluan ?? '-' }}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(auth()->user()->dept_id == 7 && $sku->status == 3)
                    <div class="hr-line-dashed"></div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-success font-bold">
                            <i class="fa fa-save"></i> UPDATE SKU & COMPLETE
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Box 3: Supervisor Action --}}
    @if(auth()->user()->position_id == 3 && auth()->user()->dept_id == $sku->dept_id && $sku->status == 1)
        <div class="ibox shadow-sm m-t-md border-bottom" style="border-top: 4px solid #f8ac59;">
            <div class="ibox-content text-center p-md">
                <h3 class="text-warning"><i class="fa fa-user-md"></i> Supervisor Decision</h3>
                <p>Please review this submission before it goes to the Department Head.</p>
                <div class="m-t-md">
                    <form id="formApproveSpv" action="{{ route('sku.approve', $sku->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="button" class="btn btn-warning btn-lg font-bold" onclick="confirmApproveSpv()">
                            <i class="fa fa-check"></i> APPROVE (NEXT TO DEPT HEAD)
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#modalReject">
                        <i class="fa fa-times"></i> REJECT
                    </button>
                </div>
            </div>
        </div>
    @endif
    
    {{-- Box 3: Dept Head Action --}}
    @if(auth()->user()->position_id == 2 && auth()->user()->dept_id == $sku->dept_id && $sku->status == 2)
        <div class="ibox shadow-sm m-t-md border-bottom">
            <div class="ibox-content text-center p-md">
                <h3><i class="fa fa-gavel"></i> Dept Head Decision</h3>
                <p>Do you approve this submission to be processed by Finance?</p>
                <div class="m-t-md">
                    <form id="formApprove" action="{{ route('sku.approve', $sku->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="button" class="btn btn-primary btn-lg" onclick="confirmApprove()">
                            <i class="fa fa-check"></i> APPROVE
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#modalReject">
                        <i class="fa fa-times"></i> REJECT
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Box 4: PPIC Final Validation --}}
    @if(auth()->user()->hasRole('AdminSKU') && $sku->status == 5)
        <div class="ibox shadow-sm m-t-md border-bottom" style="border: 2px solid #23c6c8;">
            <div class="ibox-content text-center p-md">
                <h3 class="text-info"><i class="fa fa-check-square-o"></i> PPIC Final Validation</h3>
                <p>Finance has assigned SKU numbers. Please perform final validation to complete the process.</p>
                <div class="m-t-md">
                    <form id="formFinal" action="{{ route('sku.approve', $sku->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="button" class="btn btn-info btn-lg font-bold" onclick="preValidateCheck()">
                            <i class="fa fa-rocket"></i> VALIDATE & COMPLETE SKU
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#modalReject">
                        <i class="fa fa-times"></i> PERMANENT REJECT
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- --- MODAL IMAGE PREVIEW --- --}}
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn"> {{-- Tambah animasi biar halus --}}
            {{-- Header Rapi: Judul Tengah, Close Kanan --}}
            <div class="modal-header d-flex justify-content-between align-items-center" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e6e7;">
                <h4 class="modal-title m-0" id="modalTitle" style="font-weight: bold; flex-grow: 1; text-align: center;">Image Preview</h4>
                <button type="button" class="close m-0 p-0" data-dismiss="modal" style="font-size: 28px; line-height: 1;">&times;</button>
            </div>
            
            <div class="modal-body text-center" style="background-color: #f3f3f4; padding: 20px;">
                <img id="imgPreview" src="" class="img-fluid shadow-lg" style="max-height: 80vh; max-width: 100%; border-radius: 4px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
            </div>
        </div>
    </div>
</div>

{{-- --- MODAL PDF PREVIEW --- --}}
<div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 90%; max-width: 1200px; height: 90vh; margin: 30px auto;">
        <div class="modal-content animated fadeIn" style="height: 100%;">
            
            {{-- Header Rapi --}}
            <div class="modal-header d-flex justify-content-between align-items-center" style="display: flex; justify-content: space-between; align-items: center; background-color: #2f4050; color: white;">
                <h4 class="modal-title m-0" style="font-weight: bold; flex-grow: 1; text-align: center;">PDF Document Preview</h4>
                <button type="button" class="close m-0 p-0" data-dismiss="modal" style="color: white; opacity: 0.8; font-size: 28px; line-height: 1;">&times;</button>
            </div>
            
            <div class="modal-body p-0" style="height: calc(100% - 60px); background: #525659;">
                <iframe id="pdfFrame" src="" frameborder="0" style="width: 100%; height: 100%;"></iframe>
            </div>
        </div>
    </div>
</div>

{{-- Modal Reject Upgraded --}}
<div class="modal fade" id="modalReject" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight"> {{-- Tambah animasi bounce biar eyecatching --}}
            <div class="modal-header" style="background-color: #ed5565; color: white; border-bottom: none;">
                <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="fa fa-times-circle modal-icon" style="color: white; font-size: 60px;"></i>
                <h3 class="modal-title font-bold">Reject Submission</h3>
                <small style="color: #fddddd;">This action will notify the PIC to perform a revision.</small>
            </div>
            
            <form action="{{ route('sku.reject', $sku->id) }}" method="POST" id="formReject">
                @csrf
                <div class="modal-body p-lg">
                    <div class="alert alert-warning" style="background-color: #fff9f0; border: 1px dashed #f8ac59; color: #8a6d3b;">
                        <i class="fa fa-info-circle"></i> <strong>Note:</strong> 
                        Please provide a clear reason so the requestor knows exactly what needs to be fixed.
                    </div>

                    <div class="form-group m-t-md">
                        <label class="font-bold">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="reject_reason" 
                                  id="reject_reason"
                                  class="form-control" 
                                  rows="5" 
                                  placeholder="Ex: Please provide more detailed specifications for the material and upload a clearer photo of the item..." 
                                  style="resize: none; border: 1px solid #e5e6e7; border-radius: 4px;"
                                  required></textarea>
                        <small class="text-muted" id="charCount">Recommended minimum 10 characters.</small>
                    </div>
                </div>
                <div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #e7eaec;">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger font-bold" id="btnConfirmReject">
                        <i class="fa fa-paper-plane"></i> SUBMIT REJECTION
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-muted { background-color: #f3f3f4 !important; }
    .img-thumbnail:hover { transform: scale(1.1); transition: 0.3s; cursor: zoom-in; }
    /* Membuat pembungkus tabel bisa di-scroll secara horizontal */
    .table-responsive {
        width: 100%;
        margin-bottom: 15px;
        overflow-y: hidden;
        -ms-overflow-style: -ms-autohiding-scrollbar;
        border: 1px solid #e7eaec;
    }

    /* Memastikan inputan di dalam tabel tidak berantakan saat digeser */
    .table-responsive .form-control {
        min-width: 100px;
    }

    /* Agar tabel tidak menciut di layar kecil */
    .table {
        white-space: nowrap; /* Mencegah teks turun ke bawah/wrap */
    }
    
    /* Tapi khusus untuk Spec dan Purpose, kita biarkan bisa wrap agar tidak kepanjangan */
    .table td small, .table td:nth-child(3), .table td:nth-child(11) {
        white-space: normal !important;
        min-width: 150px;
    }
    /* Pastikan SweetAlert2 selalu tampil di atas modal bootstrap */
    .swal2-container {
        z-index: 10000 !important;
    }
</style>
@endpush

@push('scripts')
<script>
    function showImage(url, title) {
        $('#imgPreview').attr('src', url);
        $('#modalTitle').text('Preview: ' + title);
        $('#imageModal').modal('show');
    }

    // Fungsi Baru untuk PDF
    function showPdf(url) {
        $('#pdfFrame').attr('src', url);
        $('#pdfModal').modal('show');
    }

    // Tambahan: Bersihkan iframe saat modal ditutup agar tidak berat
    $('#pdfModal').on('hidden.bs.modal', function () {
        $('#pdfFrame').attr('src', '');
    });

    @if(session('rejected_ok'))
        Swal.fire({
            icon: 'warning', // Gunakan warning agar warna orange/kuning
            title: 'Submission Rejected',
            text: "{{ session('rejected_ok') }}",
            confirmButtonColor: '#ed5565', // Warna merah Inspinia
            showConfirmButton: true
        });
    @endif

    function confirmApproveSpv() {
        Swal.fire({
            title: 'Confirm Supervisor Approval',
            text: "Pass this request to Dept Head for final approval?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f8ac59',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Approve!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading() }
                });
                document.getElementById('formApproveSpv').submit();
            }
        })
    }

    function confirmApprove() {
        Swal.fire({
            title: 'Confirm Approval',
            text: "Are you sure you want to approve this SKU request?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1ab394',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Approve!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading() }
                });
                document.getElementById('formApprove').submit();
            }
        })
    }

    function confirmFinal() {
        Swal.fire({
            title: 'Final Validation',
            text: "Confirm that all SKU numbers are correct? This will complete the process.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#23c6c8',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Validate & Complete!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Finalizing...',
                    text: 'Updating status to Completed',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading() }
                });
                document.getElementById('formFinal').submit();
            }
        })
    }

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Success!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
    @endif

    @if(session('fail'))
        Swal.fire({ icon: 'error', title: 'Error!', text: "{{ session('fail') }}" });
    @endif

    function preValidateCheck() {
        // 1. Show Loading
        Swal.fire({
            title: 'Checking Data...',
            text: 'Comparing with Master Product Database...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        });

        // 2. AJAX Request
        let checkUrl = "{{ route('sku.check_conflicts', $sku->id) }}";

        fetch(checkUrl)
            .then(response => response.json())
            .then(data => {
                Swal.close(); // Close loading

                if (data.has_conflict) {
                    // --- HEADER: Alert Box ---
                    let conflictHtml = '<div style="text-align: left;">';
                    
                    conflictHtml += '<div class="alert alert-danger" style="display: flex; align-items: center; padding: 10px; margin-bottom: 15px;">';
                    conflictHtml += '<i class="fa fa-exclamation-circle fa-2x" style="margin-right: 15px;"></i>';
                    conflictHtml += '<div><h4 style="margin: 0; font-weight: bold;">Conflict Detected!</h4><small>The following items are already in Master Product.</small></div>';
                    conflictHtml += '</div>';

                    // --- BODY: List Container (Scrollable) ---
                    conflictHtml += '<div style="max-height: 250px; overflow-y: auto; background: #fafafa; border: 1px solid #e7eaec; border-radius: 4px; padding: 10px 15px; margin-bottom: 15px;">';
                    
                    // Loop data conflict (Design HTML-nya sudah dari Controller)
                    data.conflicts.forEach(item => {
                        conflictHtml += item.message; 
                    });
                    
                    conflictHtml += '</div>';
                    
                    // --- FOOTER: Warning Overwrite ---
                    conflictHtml += '<p style="font-size: 13px; color: #676a6c; text-align: center;">';
                    conflictHtml += 'Proceeding will <span style="color: #ed5565; font-weight: 800; text-decoration: underline;">PERMANENTLY OVERWRITE</span> the existing data.';
                    conflictHtml += '</p>';
                    conflictHtml += '</div>';

                    // 4. Show Confirmation Modal
                    Swal.fire({
                        title: '', // Judul dikosongkan karena sudah ada di custom HTML
                        html: conflictHtml,
                        width: '550px', // Lebar ideal
                        showCancelButton: true,
                        confirmButtonColor: '#ed5565', // Merah
                        cancelButtonColor: '#fff',
                        cancelButtonText: '<span style="color: #555">Cancel & Review</span>',
                        confirmButtonText: '<i class="fa fa-save"></i> Yes, Overwrite',
                        focusCancel: true // Fokus ke tombol Cancel biar gak salah pencet
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitFinalForm();
                        }
                    });

                } else {
                    // 5. No Conflicts - Standard Confirmation
                    Swal.fire({
                        title: 'Final Validation',
                        text: "Confirm that all SKU numbers are correct? This will complete the process.",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#23c6c8',
                        confirmButtonText: 'Yes, Validate & Complete!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitFinalForm();
                        }
                    });
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Failed to check data conflicts.', 'error');
                console.error(error);
            });
    }

    function submitFinalForm() {
        Swal.fire({
            title: 'Processing...',
            text: 'Updating Master Product & Status',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        });
        document.getElementById('formFinal').submit();
    }

    // Show loading when finance or reject form is submitted
    $('#formReject, #formFinance').on('submit', function() {
        // Sembunyikan modal penolakan jika sedang aktif agar tidak menghalangi loading
        if ($(this).attr('id') === 'formReject') {
            $('#modalReject').modal('hide');
        }

        Swal.fire({
            title: 'Processing...',
            text: 'Mengirim notifikasi email, mohon tunggu sebentar.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    });
</script>
@endpush