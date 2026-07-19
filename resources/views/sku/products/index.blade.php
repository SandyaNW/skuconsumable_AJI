@extends('layouts.app-master')

@section('content')
<div class="row">
    <div class="col-lg-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><i class="fa fa-check"></i> {{ session('success') }}</div>
        @endif

        <div class="row m-b-sm">
            <div class="col-md-6">
                <div class="btn-group">
                    @if(auth()->user()->hasRole('AdminSKU'))
                        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Product</a>
                        <button type="button" class="btn btn-default btn-sm" data-toggle="collapse" data-target="#filter-panel"><i class="fa fa-filter"></i> Filter & Import</button>
                    @else
                        <button type="button" class="btn btn-default btn-sm" data-toggle="collapse" data-target="#filter-panel"><i class="fa fa-filter"></i> Filter</button>
                    @endif
                </div>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('products.export') }}" class="btn btn-outline btn-default btn-sm"><i class="fa fa-download"></i> Export Excel</a>
            </div>
        </div>

        <div id="filter-panel" class="collapse {{ request('search') || request('category') ? 'in' : '' }}">
            <div class="ibox border-left-right border-top-bottom">
                <div class="ibox-content p-m grey-bg">
                    <div class="row">
                        <div class="{{ auth()->user()->hasRole('AdminSKU') ? 'col-md-4 border-right' : 'col-md-12' }}">
                            <label class="font-bold">Search & Filter</label>
                            <form action="{{ route('products.index') }}" method="GET">
                                <div class="form-group">
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Part No/SKU/Nama..." class="form-control input-sm">
                                </div>
                                <div class="form-group">
                                    <select name="category" class="form-control input-sm">
                                        <option value="">- All Categories -</option>
                                        @foreach(\App\Models\Product::CATEGORIES as $key => $value)
                                            <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-sm btn-info btn-block">Apply Filter</button>
                                <a href="{{ route('products.index') }}" class="btn btn-xs btn-link btn-block text-muted">Reset Filter</a>
                            </form>
                        </div>
                        @if(auth()->user()->hasRole('AdminSKU'))
                        <div class="col-md-5">
                            <label class="font-bold text-navy">Bulk Import (Excel)</label>
                            <p class="text-muted small">Gunakan fitur ini untuk upload data massal. Sistem akan mengecek duplikat sebelum disimpan.</p>
                            <div class="row">
                                <div class="col-xs-6" style="padding-right: 5px;">
                                    <button type="button" class="btn btn-sm btn-primary btn-block" data-toggle="modal" data-target="#importModal">
                                        <i class="fa fa-file-excel-o"></i> Open Import Tool
                                    </button>
                                </div>
                                <div class="col-xs-6" style="padding-left: 5px;">
                                    <a href="{{ route('products.download_template') }}" class="btn btn-sm btn-success btn-outline btn-block">
                                        <i class="fa fa-download"></i> Unduh Template
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="ibox shadow-sm">
            <div class="ibox-title" style="border-top: 4px solid #23c6c8;">
                <h5><i class="fa fa-database text-info"></i> MASTER PRODUCT DATABASE</h5>
                <div class="ibox-tools">
                    <span class="text-muted m-r-sm">Total: {{ $products->total() }} items</span>
                </div>
            </div>
            
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover text-nowrap" id="masterProductTable">
                        <thead>
                            <tr>
                                <th width="30">No</th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'product_code', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                        Part Number <i class="fa fa-sort{{ request('sort') == 'product_code' ? (request('direction') == 'asc' ? '-asc' : '-desc') : '' }} text-muted"></i>
                                    </a>
                                </th>
                                <th>SKU</th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'item_name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                                        Item Name <i class="fa fa-sort{{ request('sort') == 'item_name' ? (request('direction') == 'asc' ? '-asc' : '-desc') : '' }} text-muted"></i>
                                    </a>
                                </th>
                                <th>UOM</th>
                                <th>Category</th>
                                <th class="text-right">Usage/Month</th>
                                <th>Source</th>
                                <th class="text-center">Status</th>
                                <th>Added</th>
                                @if(auth()->user()->hasRole('AdminSKU'))
                                <th class="text-center">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $index => $p)
                            <tr>
                                <td>{{ $products->firstItem() + $index }}</td>
                                <td class="font-bold text-navy">{{ $p->product_code }}</td>
                                <td class="text-muted">{{ $p->sku_code }}</td>
                                <td>{{ $p->item_name }}</td>
                                <td><span class="badge badge-plain">{{ $p->uom }}</span></td>
                                <td><span class="label label-default">{{ $p->category }}</span></td>
                                <td class="text-right font-bold text-navy">{{ $p->usage_month !== null ? number_format($p->usage_month, 0) : '-' }}</td>
                                <td class="text-center">
                                    @if($p->input_source == 'submission')
                                        <span class="label label-primary" title="Added via SKU Submission/Manual"><i class="fa fa-paper-plane"></i> SUBMISSION</span>
                                    @else
                                        <span class="label label-warning" title="Imported from Existing Data"><i class="fa fa-archive"></i> EXISTING</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(($p->status ?? 'active') == 'active')
                                        <span class="label label-info shadow-xs" style="background-color: #23c6c8; color: white;"><i class="fa fa-check-circle"></i> ACTIVE</span>
                                    @else
                                        <span class="label label-danger shadow-xs" style="background-color: #ed5565; color: white;"><i class="fa fa-times-circle"></i> INACTIVE</span>
                                    @endif
                                </td>
                                <td>{{ $p->created_at->format('d/m/y') }}</td>
                                @if(auth()->user()->hasRole('AdminSKU'))
                                <td class="text-center">
                                    <a href="{{ route('products.edit', $p->id) }}" class="btn btn-xs btn-white border-info"><i class="fa fa-pencil text-info"></i> Edit</a>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr><td colspan="{{ auth()->user()->hasRole('AdminSKU') ? 11 : 10 }}" class="text-center py-5">Belum ada data di Master Product.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="m-t-md">{{ $products->appends(request()->query())->links() }}</div>
            </div>
        </div>
    </div>
</div>

{{-- modal konfirmasi export --}}
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Import Master Product</h4>
            </div>
            <div class="modal-body">
                <form id="formImport" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group text-center">
                        <label class="btn btn-default btn-file btn-block" style="border: 2px dashed #ccc; padding: 20px;">
                            <i class="fa fa-cloud-upload fa-3x text-muted"></i><br>
                            <span class="font-bold">Klik untuk pilih file Excel</span>
                            <input type="file" name="file" style="display: none;" required accept=".xlsx, .xls, .csv" onchange="$('#fileNameDisplay').text(this.files[0].name)">
                        </label>
                        <p id="fileNameDisplay" class="text-info m-t-sm font-bold"></p>
                    </div>
                    <div class="alert alert-warning text-center" style="font-size: 11px;">
                        <i class="fa fa-info-circle"></i> Sistem akan melakukan pengecekan duplikat (Pre-flight check) sebelum data disimpan ke database.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="checkImportFile()">
                    <i class="fa fa-search"></i> Check File & Process
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .grey-bg { background-color: #f9f9f9; border: 1px solid #e7eaec; border-radius: 4px; margin-bottom: 10px; }
    #masterProductTable thead th a { color: #676a6c; display: block; width: 100%; }
    #masterProductTable thead th { background-color: #f3f3f4; font-size: 11px; vertical-align: middle !important; }
    #masterProductTable tbody td { vertical-align: middle; font-size: 12px; }
    .badge-plain { border: 1px solid #e7eaec; background: transparent; color: #676a6c; font-weight: normal; }
    .border-right { border-right: 1px solid #e7eaec; }
</style>
@endpush

@push('scripts')
<script>
function checkImportFile() {
    let formData = new FormData($('#formImport')[0]);
    
    // 1. Loading
    $('#importModal').modal('hide');
    Swal.fire({ title: 'Analyzing File...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });

    // 2. Kirim ke Preview
    $.ajax({
        url: "{{ route('products.import_preview') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            Swal.close();

            if (response.status == 'success') {
                
                // KASUS A: Jika ada Duplikat
                if (response.conflict_count > 0) {
                    
                    let html = `<div style="text-align:left">`;
                    html += `<div class="alert alert-warning">Found <b>${response.conflict_count}</b> duplicate items out of ${response.total_rows} rows.</div>`;
                    
                    // List Preview Konflik
                    html += `<div style="max-height: 200px; overflow-y:auto; background:#f9f9f9; padding:10px; border:1px solid #ddd; margin-bottom:10px;">`;
                    html += `<small class="text-muted">Preview Duplicates:</small><ul style="padding-left:15px; font-size:12px;">`;
                    
                    // Tampilkan max 5 contoh saja biar gak penuh
                    response.conflicts.slice(0, 5).forEach(c => {
                        html += `<li>Row ${c.row}: <b>${c.code}</b> <br> <span class="text-danger">Existing: ${c.old_name}</span> <i class="fa fa-arrow-right"></i> <span class="text-success">New: ${c.new_name}</span></li>`;
                    });
                    
                    if(response.conflicts.length > 5) html += `<li>...and ${response.conflicts.length - 5} more.</li>`;
                    html += `</ul></div>`;
                    
                    html += `<p>What would you like to do with these duplicates?</p></div>`;

                    Swal.fire({
                        title: 'Duplicates Found!',
                        html: html,
                        icon: 'question',
                        showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Overwrite All', // Update data lama dengan excel baru
                        denyButtonText: 'Skip Duplicates',  // Biarkan data lama, masukkan yang baru saja
                        cancelButtonText: 'Cancel Import',
                        confirmButtonColor: '#f8ac59', // Orange
                        denyButtonColor: '#1ab394',    // Hijau
                    }).then((result) => {
                        if (result.isConfirmed) {
                            executeImport(response.temp_path, 'overwrite');
                        } else if (result.isDenied) {
                            executeImport(response.temp_path, 'skip');
                        }
                    });

                } else {
                    // KASUS B: Aman (Gak ada duplikat)
                    executeImport(response.temp_path, 'overwrite'); 
                }
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(err) {
            Swal.fire('Error', 'Failed to upload file.', 'error');
        }
    });
}

function executeImport(path, action) {
    Swal.fire({ title: 'Importing...', text: 'Please wait', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });

    $.ajax({
        url: "{{ route('products.import_process') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            temp_path: path,
            action: action
        },
        success: function(res) {
            // --- PERBAIKAN DISINI ---
            // Cek dulu statusnya! Jangan asal Success.
            if (res.status == 'success') {
                Swal.fire('Success', res.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                // Kalau server bilang error (misal file expired/gagal baca)
                Swal.fire('Failed!', res.message, 'error'); 
            }
        },
        error: function(err) {
            // Ini error jaringan / server crash (500)
            let msg = 'Import process failed.';
            if(err.responseJSON && err.responseJSON.message) msg = err.responseJSON.message;
            Swal.fire('Critical Error', msg, 'error');
        }
    });
}
</script>
@endpush