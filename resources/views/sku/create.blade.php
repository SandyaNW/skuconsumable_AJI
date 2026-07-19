@extends('layouts.app-master')

@section('content')

    <div class="ibox shadow-sm">
        <div class="ibox-title" style="border-top: 4px solid #1ab394;">
            <h5><i class="fa fa-edit text-navy"></i> SKU SUBMISSION FORM</h5>
            <div class="ibox-tools">
                <span class="label label-primary">Status: New Request</span>
            </div>
        </div>
        <div class="ibox-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <i class="fa fa-check"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('fail'))
                <div class="alert alert-danger alert-dismissible">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <i class="fa fa-times"></i> {{ session('fail') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('sku.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('sku.partials.form_fields') {{-- Call partial fields --}}

                <div class="ibox-footer text-right">
                    <button type="submit" class="btn btn-warning font-bold">SUBMIT DATA</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>

        /* Mengatasi select2 yang overflow di dalam tabel */
        .table-responsive {
            overflow: visible !important;
        }
        
        /* Biar dropdown select2 gak kepotong */
        .ibox-content {
            overflow: visible !important;
        }

        /* Minimalisir padding tabel biar inputan muat banyak */
        .table td {
            padding: 4px !important;
            vertical-align: middle !important;
        }

        /* Style khusus input di dalam tabel */
        .form-input {
            width: 100%;
            border: none;
            border-bottom: 1px solid #e5e6e7;
            font-size: 12px;
            padding: 4px;
        }
        
        .form-input:focus {
            outline: none;
            border-bottom: 1px solid #1ab394;
        }
        /* 1. Paksa tinggi dropdown dan kasih scrollbar */
        .select2-results__options {
            max-height: 200px !important;
            overflow-y: auto !important;
        }

        /* 2. Hilangkan border agar minimalis sesuai keinginan lo */
        .select2-container--bootstrap4 .select2-selection--single {
            border: none !important;
            border-bottom: 1px solid #e5e6e7 !important; /* Biar ada garis bawah tipis aja */
            border-radius: 0 !important;
            background-color: transparent !important;
            height: 30px !important;
        }

        /* 3. Atur teks di dalam Select2 */
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 30px !important;
            font-size: 12px;
            color: #676a6c !important;
            padding-left: 0px !important;
        }

        /* 4. Samakan lebar dengan form-input lainnya */
        .select2-container {
            width: 100% !important;
            display: block;
        }
        
    </style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {
        // Ambil data master dari PHP untuk dipakai di JS
        const masterCategories = @json(\App\Models\SkuDetail::getCategories());
        const masterUoms = @json(\App\Models\SkuDetail::getUoms());
        
        let rowIdx = $('#table-body tr').length;

        // --- 1. Fungsi Inisialisasi Select2 ---
        function initSelect2(element) {
            // Jika element spesifik diberikan, init element itu saja.
            // Jika tidak, init semua .select2-basic yang belum ter-init
            let target = element ? $(element) : $('.select2-basic');

            target.select2({
                width: '100%',
                placeholder: "Choose Here",
                allowClear: true,
                minimumResultsForSearch: 8
            });
        }

        // Init row pertama saat halaman load
        initSelect2();

        // --- 2. Function Add Row ---
        $('#add-row').click(function () {
            // Generate Options untuk UOM
            let uomOptions = '<option value="">-- Select --</option>';
            Object.entries(masterUoms).forEach(([key, label]) => {
                uomOptions += `<option value="${key}">${key} - ${label}</option>`;
            });

            // Generate Options untuk Category
            let catOptions = '<option value="">-- Select --</option>';
            Object.entries(masterCategories).forEach(([key, label]) => {
                catOptions += `<option value="${key}">${label}</option>`;
            });

            // Template HTML (Harus sama strukturnya dengan item_row.blade.php)
            let newRowHtml = `
            <tr class="item-row">
                <td class="text-center row-number">${rowIdx + 1}</td>
                <td>
                    <input type="text" name="details[${rowIdx}][item]" class="form-input autocomplete-item" placeholder="Enter item..." autocomplete="off" required>
                </td>
                <td><input type="text" name="details[${rowIdx}][spec]" class="form-input" placeholder="Enter specs..."></td>
                <td><input type="number" name="details[${rowIdx}][qty]" class="form-input text-center" placeholder="0" required></td>
                
                <td>
                    <select name="details[${rowIdx}][uom]" class="form-input select2-basic" required>
                        ${uomOptions}
                    </select>
                </td>

                <td>
                    <select name="details[${rowIdx}][category]" class="form-input select2-basic" required>
                        ${catOptions}
                    </select>
                </td>

                <td><input type="number" name="details[${rowIdx}][usage]" class="form-input text-center" placeholder="0"></td>
                <td>
                    <input type="file" name="details[${rowIdx}][lampiran_foto]" class="form-input file-input" accept=".jpg,.pdf">
                    <div class="preview-container"></div>
                    <small class="text-muted" style="display:block; font-size:9px;">JPG & PDF (Max 2MB)</small>
                </td>
                <td><input type="text" name="details[${rowIdx}][keperluan]" class="form-input" placeholder="Purpose..."></td>
                <td><input type="date" name="details[${rowIdx}][due_date]" class="form-input"></td>
                <td><input type="text" name="details[${rowIdx}][status_item]" class="form-input" readonly value="New"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-xs btn-remove-row"><i class="fa fa-times"></i></button>
                </td>
            </tr>`;

            // --- FIX PENTING DISINI ---
            // Ubah string HTML jadi jQuery Object dulu sebelum di-append
            let $newRow = $(newRowHtml);
            
            // Append ke tabel
            $('#table-body').append($newRow);

            // Init Select2 HANYA pada row baru ini agar performa bagus
            // Kita cari class .select2-basic DI DALAM $newRow
            initSelect2($newRow.find('.select2-basic'));

            rowIdx++;
            reorderRows();
        });

        // --- 3. Remove Row ---
        $(document).on('click', '.btn-remove-row', function () {
            if ($('#table-body tr').length > 1) {
                $(this).closest('tr').remove();
                reorderRows();
            } else {
                // Opsional: Pakai SweetAlert atau Alert biasa
                alert("Minimal harus ada 1 item!");
            }
        });

        // --- 4. Reorder Index ---
        function reorderRows() {
            $('#table-body tr').each(function (index) {
                // Update nomor urut
                $(this).find('.row-number').text(index + 1);
                
                // Update atribut 'name' pada input/select agar index array urut (0, 1, 2...)
                $(this).find('input, select, textarea').each(function() {
                    let name = $(this).attr('name');
                    if (name) {
                        // Regex ganti details[angka] jadi details[index_baru]
                        let newName = name.replace(/details\[\d+\]/, 'details[' + index + ']');
                        $(this).attr('name', newName);
                    }
                });
            });
            // Update counter global
            rowIdx = $('#table-body tr').length;
        }

        // --- 5. File Preview (Tetap sama) ---
        $(document).on('change', '.file-input', function () {
            const file = this.files[0];
            const container = $(this).siblings('.preview-container');

            if (file) {
                const ext = file.name.split('.').pop().toLowerCase();
                if (['jpg', 'jpeg', 'png'].includes(ext)) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        container.html(`<img src="${e.target.result}" style="max-height:50px; margin-top:5px; border:1px solid #ddd;">`);
                    }
                    reader.readAsDataURL(file);
                } else {
                    let icon = (ext === 'pdf') ? 'fa-file-pdf-o text-danger' : 'fa-file-excel-o text-success';
                    container.html(`<div class="m-t-xs"><i class="fa ${icon}"></i> <small>${file.name}</small></div>`);
                }
            } else {
                container.empty();
            }
        });

        // --- 6. Global Autocomplete AJAX Logic ---
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

        // --- 7. Form Submit Loading Screen ---
        $('form').on('submit', function() {
            Swal.fire({
                title: 'Processing...',
                text: 'Sedang menyimpan data dan mengirim notifikasi email, mohon tunggu sebentar.',
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
</script>
@endpush

