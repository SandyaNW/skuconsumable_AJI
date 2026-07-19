@extends('layouts.app-master')

@section('content')
<div class="row">
    {{-- Sidebar Info --}}
    <div class="col-lg-3">
        <div class="ibox border-bottom">
            <div class="ibox-content">
                <div class="file-manager">
                    <h5>REVISION GUIDELINES</h5>
                    <ol class="p-l-md">
                        <li>Check the <strong>Rejection Reason</strong> carefully.</li>
                        <li>Update the necessary fields in the item table.</li>
                        <li>You can keep existing photos or upload new ones.</li>
                        <li>Click <strong>Re-Submit</strong> to start a new approval cycle.</li>
                    </ol>
                    <div class="hr-line-dashed"></div>
                    <h5 class="m-t-md">Submission Summary</h5>
                    <ul class="folder-list m-b-md" style="padding: 0">
                        <li><a href="javascript:void(0)"><i class="fa fa-tag"></i> Total Items: <span class="label label-info pull-right">{{ $sku->details->count() }}</span></a></li>
                        <li><a href="javascript:void(0)"><i class="fa fa-calendar"></i> Orig. Date: <span class="pull-right">{{ date('d/m/Y', strtotime($sku->issue_date)) }}</span></a></li>
                        <li><a href="javascript:void(0)"><i class="fa fa-user"></i> PIC: <span class="pull-right">{{ $sku->nama }}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Form --}}
    <div class="col-lg-9">
        <div class="ibox border-bottom shadow-sm">
            <div class="ibox-title" style="background: #ed5565; color: white;">
                <h5><i class="fa fa-edit"></i> REVISION FORM - #SKU/{{ $sku->id }}/{{ date('Y') }}</h5>
            </div>
            <div class="ibox-content">
                {{-- Alasan Reject dengan style lebih 'galak' --}}
                <div class="alert alert-danger" style="background-color: #f2dede; border-color: #ebccd1; color: #a94442;">
                    <div class="row">
                        <div class="col-sm-1 text-center">
                            <i class="fa fa-warning fa-3x"></i>
                        </div>
                        <div class="col-sm-11">
                            <h4 class="m-t-none">Rejection Feedback:</h4>
                            <p class="font-bold italic">"{{ $sku->reject_reason }}"</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('sku.update', $sku->id) }}" method="POST" enctype="multipart/form-data" id="reviseForm">
                    @csrf
                    @method('PUT')

                    <div class="row m-b-lg">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-bold text-navy">Requestor Name</label>
                                <input type="text" name="nama" value="{{ $sku->nama }}" class="form-control" readonly style="background-color: #f9f9f9">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-bold text-navy">New Submission Date (Today)</label>
                                <input type="text" value="{{ now()->format('d/m/Y') }}" class="form-control" readonly style="background-color: #f9f9f9">
                                <small class="text-muted italic">*System will automatically update the issue date to today.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-bold text-navy">NPK</label>
                                <input type="text" name="nama" value="{{ $sku->npk }}" class="form-control" readonly style="background-color: #f9f9f9">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-bold text-navy">Department</label>
                                <input type="text" name="nama" value="{{ $sku->departement }}" class="form-control" readonly style="background-color: #f9f9f9">
                            </div>
                        </div>
                    </div>
                    <div class="row m-b-md">
                        <div class="col-lg-12">
                            <div class="ibox shadow-sm border-left-right border-top-bottom">
                                <div class="ibox-title" style="background-color: #fafafa;">
                                    <h5><i class="fa fa-commenting text-warning"></i> Revision Remarks</h5>
                                </div>
                                <div class="ibox-content">
                                    <div class="form-group mb-0">
                                        {{-- Input ini akan dikirim saat klik tombol RE-SUBMIT REVISION --}}
                                        <textarea name="remarks" class="form-control" rows="2" 
                                                placeholder="Tuliskan penjelasan Anda di sini.."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive" style="overflow-x: auto !important; overflow-y: visible !important;">
                        <table class="table table-bordered" style="font-size: 11px; min-width: 1100px;">
                            <thead>
                                <tr class="bg-light">
                                    <th width="40" class="text-center">No</th>
                                    <th width="180">Item Name</th>
                                    <th width="200">Specification</th>
                                    <th width="70">Qty</th>
                                    <th width="80">UOM</th>
                                    <th width="120">Category</th>
                                    <th width="70">Usage</th>
                                    <th width="150" class="text-center">Attachment</th>
                                    <th width="200">Purpose</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sku->details as $index => $detail)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;">
                                        {{ $index + 1 }}
                                        <input type="hidden" name="details[{{$index}}][id]" value="{{ $detail->id }}">
                                    </td>
                                    <td>
                                        <input type="text" name="details[{{$index}}][item_name]" class="form-control input-sm autocomplete-item" value="{{ $detail->item_name }}" autocomplete="off" required>
                                        
                                    </td>
                                    <td>
                                        <textarea name="details[{{$index}}][specification]" class="form-control input-sm" rows="2">{{ $detail->specification }}</textarea>
                                    </td>
                                    <td>
                                        <input type="number" name="details[{{$index}}][qty]" class="form-control input-sm text-center" value="{{ $detail->qty }}" required>
                                    </td>
                                    <td>
                                        {{-- DROPDOWN UOM --}}
                                        <select name="details[{{$index}}][uom]" class="form-control input-xs" required>
                                            @foreach($uoms as $key => $label)
                                                <option value="{{ $key }}" {{ $detail->uom == $key ? 'selected' : '' }}>{{ $key }} - {{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        {{-- DROPDOWN CATEGORY --}}
                                        <select name="details[{{$index}}][category]" class="form-control input-xs" required>
                                            @foreach($categories as $key => $label)
                                                <option value="{{ $key }}" {{ $detail->category == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="details[{{$index}}][usage]" class="form-control input-sm text-center" value="{{ $detail->usage }}">
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        {{-- Row Atas: Preview File Lama --}}
                                        <div class="m-b-xs">
                                            @if($detail->lampiran_foto)
                                                @php $ext = pathinfo($detail->lampiran_foto, PATHINFO_EXTENSION); @endphp
                                                @if(in_array($ext, ['jpg']))
                                                    <img src="{{ asset('storage/'.$detail->lampiran_foto) }}" class="img-thumbnail img-preview-trigger" style="width: 35px; height: 35px; cursor:pointer;">
                                                @else
                                                    <button type="button" class="btn btn-xs btn-danger btn-outline" onclick="showPdf('{{ asset('storage/'.$detail->lampiran_foto) }}')">
                                                        <i class="fa fa-file-pdf-o"></i> PDF
                                                    </button>
                                                @endif
                                                <input type="hidden" name="details[{{$index}}][old_photo]" value="{{ $detail->lampiran_foto }}">
                                            @endif
                                        {{-- Row Bawah: Input File Baru --}}
                                        <input type="file" name="details[{{$index}}][photo]" class="form-control input-sm" style="font-size: 10px;">
                                        <small class="text-muted" style="font-size: 9px;">Upload to change</small>
                                    </td>
                                    <td>
                                        <textarea name="details[{{$index}}][keperluan]" class="form-control input-sm" rows="2">{{ $detail->keperluan }}</textarea>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="hr-line-dashed"></div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('sku.index') }}" class="btn btn-white"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
                            <button type="submit" class="btn btn-primary font-bold btn-lg" id="btnSubmit">
                                <i class="fa fa-paper-plane"></i> RE-SUBMIT REVISION
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PREVIEW (Combined Image & PDF) --}}
<div class="modal fade" id="modalPreview" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 90%; max-width: 1200px; margin: 30px auto;">
        <div class="modal-content animated fadeIn">
            
            {{-- Header Dinamis (Warna akan diubah via JS) --}}
            <div class="modal-header d-flex justify-content-between align-items-center" id="modalHeader"
                 style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e6e7; padding: 15px 20px;">
                
                <h4 class="modal-title m-0" id="modalTitleLabel" style="font-weight: bold; flex-grow: 1; text-align: center;">File Preview</h4>
                <button type="button" class="close m-0 p-0" data-dismiss="modal" style="font-size: 28px; line-height: 1; opacity: 0.7;">&times;</button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-0 text-center" id="modalBody" style="min-height: 500px; background-color: #f3f3f4; position: relative;">
                
                {{-- Container Gambar --}}
                <div id="image-viewer" style="display:none; padding: 20px;">
                    <img id="img-large" src="" class="img-fluid shadow-lg" 
                         style="max-height: 80vh; max-width: 100%; border-radius: 4px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
                </div>

                {{-- Container PDF --}}
                <div id="pdf-viewer" style="display:none; height: 85vh;">
                    <iframe id="pdf-frame" src="" width="100%" height="100%" frameborder="0"></iframe>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection



@push('styles')
<style>
.custom-table thead th {
        background-color: #f3f3f4;
        text-align: center;
        vertical-align: middle !important;
        text-transform: uppercase;
        font-size: 10px;
    }

    .input-xs {
        height: 25px;
        padding: 2px 5px;
        font-size: 11px;
        border-radius: 2px;
    }
    textarea.input-xs {
        height: auto;
    }
    .table > tbody > tr > td {
        padding: 8px 4px !important;
        vertical-align: middle !important;
    }
    .file-preview-box {
        min-height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .custom-table tbody td {
        vertical-align: middle !important;
        padding: 5px !important;
    }
    .form-control.input-sm {
        border-radius: 2px;
        border: 1px solid #e5e6e7;
    }
    .bg-light { background-color: #f9f9f9 !important; }
    #btnSubmit:hover { background-color: #18a689 !important; transition: 0.3s; }
    /* Modal Close Button Styling */
    .modal-header .close:hover {
        opacity: 1;
        color: #ed5565; /* Merah saat hover */
        transition: 0.2s;
    }
    
    /* Helper class buat ganti warna header via JS */
    .bg-dark-header {
        background-color: #2f4050 !important;
        color: white !important;
        border-bottom: none !important;
    }
    .bg-light-header {
        background-color: #ffffff !important;
        color: inherit !important;
        border-bottom: 1px solid #e5e6e7 !important;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // 1. ZOOM IMAGE CLICK
    $('.img-preview-trigger').click(function() {
        let src = $(this).attr('src');
        
        // Atur Konten
        $('#pdf-viewer').hide();
        $('#image-viewer').show();
        $('#img-large').attr('src', src);
        
        // Ubah Header jadi Terang (Light)
        $('#modalHeader').removeClass('bg-dark-header').addClass('bg-light-header');
        $('#modalHeader .close').css('color', ''); // Reset warna tombol close
        $('#modalTitleLabel').text('Image Preview');
        $('#modalBody').css('background-color', '#f3f3f4'); // Background abu muda

        $('#modalPreview').modal('show');
    });

    // 2. Global Autocomplete AJAX Logic
    if (!$('#global-autocomplete-suggestions').length) {
        $('body').append('<div id="global-autocomplete-suggestions" style="display: none; position: absolute; z-index: 10000; background: white; border: 1px solid #e5e6e7; max-height: 200px; overflow-y: auto; box-shadow: 0 4px 10px rgba(0,0,0,0.15); border-radius: 4px;"></div>');
    }

    let xhr = null;

    $(document).on('keyup', '.autocomplete-item', function() {
        let $input = $(this);
        let query = $input.val().trim();
        let $suggestions = $('#global-autocomplete-suggestions');

        if (query.length < 2) {
            $suggestions.empty().hide();
            return;
        }

        // Position suggestions exactly below the active input
        let offset = $input.offset();
        let height = $input.outerHeight();
        let width = $input.outerWidth();

        $suggestions.css({
            top: (offset.top + height) + 'px',
            left: offset.left + 'px',
            width: width + 'px'
        }).data('active-input', $input);

        if (xhr) {
            xhr.abort();
        }

        xhr = $.ajax({
            url: "{{ route('sku.search_master_products') }}",
            type: "GET",
            data: { q: query },
            dataType: "json",
            success: function(data) {
                $suggestions.empty();
                if (data.length === 0) {
                    $suggestions.append(`
                        <div class="autocomplete-suggestion-item text-muted font-bold" style="padding: 8px 12px; cursor: default; background: #fafafa; border-bottom: 1px solid #eee;">
                            <i class="fa fa-info-circle text-warning"></i> Belum ada di Master Product
                        </div>
                    `);
                    $suggestions.show();
                    return;
                }

                data.forEach(function(item) {
                    let specText = item.specification ? ` - <small class="text-muted">${item.specification}</small>` : '';
                    let codeBadge = `<span class="label label-warning" style="font-size: 9px; padding: 2px 4px; margin-right: 5px;">${item.product_code}</span>`;
                    let html = `
                        <div class="autocomplete-suggestion-item" 
                             data-name="${item.item_name}" 
                             data-spec="${item.specification || ''}" 
                             data-uom="${item.uom}" 
                             data-category="${item.category}" 
                             style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #eee; transition: background 0.2s;"
                             onmouseover="this.style.backgroundColor='#f5f5f5'"
                             onmouseout="this.style.backgroundColor='white'">
                            ${codeBadge} <strong>${item.item_name}</strong>${specText}
                        </div>
                    `;
                    $suggestions.append(html);
                });
                $suggestions.show();
            }
        });
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.autocomplete-item, #global-autocomplete-suggestions').length) {
            $('#global-autocomplete-suggestions').hide();
        }
    });

    $(window).on('scroll resize', function() {
        let $suggestions = $('#global-autocomplete-suggestions');
        if ($suggestions.is(':visible')) {
            let $input = $suggestions.data('active-input');
            if ($input && $input.length) {
                let offset = $input.offset();
                let height = $input.outerHeight();
                $suggestions.css({
                    top: (offset.top + height) + 'px',
                    left: offset.left + 'px'
                });
            }
        }
    });

    $(document).on('click', '.autocomplete-suggestion-item', function() {
        let $item = $(this);
        let name = $item.data('name');
        let spec = $item.data('spec');
        let uom = $item.data('uom');
        let category = $item.data('category');

        if (!name) return;

        let $suggestions = $('#global-autocomplete-suggestions');
        let $input = $suggestions.data('active-input');
        if ($input && $input.length) {
            let $row = $input.closest('tr');
            
            $input.val(name);
            
            let $specInput = $row.find('input[name*="[spec]"], textarea[name*="[specification]"]');
            if ($specInput.length) {
                $specInput.val(spec);
            }

            let $uomSelect = $row.find('select[name*="[uom]"]');
            if ($uomSelect.length) {
                $uomSelect.val(uom);
                if ($uomSelect.hasClass('select2-hidden-accessible')) {
                    $uomSelect.trigger('change');
                }
            }

            let $catSelect = $row.find('select[name*="[category]"]');
            if ($catSelect.length) {
                $catSelect.val(category);
                if ($catSelect.hasClass('select2-hidden-accessible')) {
                    $catSelect.trigger('change');
                }
            }
        }

        $suggestions.empty().hide();
    });

    // 3. SweetAlert2 Loading State on Submit
    $('#reviseForm').on('submit', function() {
        Swal.fire({
            title: 'Processing...',
            text: 'Sedang menyimpan revisi data dan mengirim notifikasi email, mohon tunggu sebentar.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    });
});

// 2. SHOW PDF FUNCTION
function showPdf(url) {
    // Atur Konten
    $('#image-viewer').hide();
    $('#pdf-viewer').show();
    $('#pdf-frame').attr('src', url);

    // Ubah Header jadi Gelap (Dark)
    $('#modalHeader').removeClass('bg-light-header').addClass('bg-dark-header');
    $('#modalHeader .close').css('color', 'white'); // Tombol close jadi putih
    $('#modalTitleLabel').text('PDF Document Preview');
    $('#modalBody').css('background-color', '#525659'); // Background abu gelap khas PDF Viewer

    $('#modalPreview').modal('show');
}

// Reset iframe saat modal tutup biar ringan
$('#modalPreview').on('hidden.bs.modal', function () {
    $('#pdf-frame').attr('src', '');
    $('#img-large').attr('src', '');
});
</script>
@endpush