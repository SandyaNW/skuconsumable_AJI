@extends('layouts.app-master')

@section('content')
@if(session('fail'))
    <div class="alert alert-danger alert-dismissible">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <i class="fa fa-times-circle"></i> {{ session('fail') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <i class="fa fa-check-circle"></i> {{ session('success') }}
    </div>
@endif
<div class="ibox shadow-sm">
    <div class="ibox-title" style="border-top: 4px solid #1ab394;">
        <h5><i class="fa fa-list text-navy"></i> SKU MONITORING PAGE</h5>
    </div>

    <div class="ibox-content m-b-sm border-bottom">
        <form id="filter-form" method="GET" action="{{ route('sku.index') }}">
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <select name="status" id="status-filter" class="form-control">
                            <option value="">-- All Status --</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Waiting Supervisor</option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Waiting Dept Head</option>
                            <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Process Finance</option>
                            <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>Rejected</option>
                            <option value="5" {{ request('status') == '5' ? 'selected' : '' }}>Waiting PPIC</option>
                            <option value="6" {{ request('status') == '6' ? 'selected' : '' }}>Completed</option>
                            <option value="7" {{ request('status') == '7' ? 'selected' : '' }}>Final Rejected</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="control-label">Category</label>
                        <select name="category" class="form-control">
                            <option value="">-- All Categories --</option>
                            {{-- Narik langsung dari static method di Model SKUDetail --}}
                            @foreach(\App\Models\SKUDetail::getCategories() as $key => $label)
                                <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label">Date Range</label>
                        <div class="input-daterange input-group">
                            <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                            <span class="input-group-addon">to</span>
                            <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-4 text-right">
                    <label class="control-label d-block">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                        <a href="{{ route('sku.index') }}" class="btn btn-white"> Reset</a>
                        @if(auth()->user()->position_id != 2)
                            <a href="{{ route('sku.export', request()->all()) }}" class="btn btn-success">
                                <i class="fa fa-file-excel-o"></i> Export Excel
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="ibox-content">
        <div class="row mb-4">
            <div class="col-md-8">
                <h3 class="m-t-none">SKU Submission List</h3>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('sku.create') }}" class="btn btn-primary btn-sm font-bold">
                    <i class="fa fa-plus"></i> NEW SUBMISSION
                </a>
            </div>
        </div>

        <div class="table-responsive" style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table class="table table-striped table-bordered table-hover" id="sku-table" style="width: 100%; min-width: 1500px;" >
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th style="width: 150px;">ID Submission</th>
                        <th style="width: 120px;">Requestor</th>
                        <th style="width: 120px;">Section</th>
                        <th style="min-width: 200px;">Item</th>
                        <th style="min-width: 250px;">Specification</th>
                        <th style="width: 100px;">Product Code</th>
                        <th style="width: 100px;">SKU</th>
                        <th class="text-center" style="width: 70px;">QTY</th>
                        <th style="width: 70px;">UOM</th>
                        <th style="width: 120px;">Category</th>
                        <th class="text-center" style="width: 80px;">Attachment</th>
                        <th style="width: 100px;">Usage/Month</th>
                        <th style="min-width: 150px;">Purpose</th>
                        <th style="width: 100px;">Due Date</th>
                        <th class="text-center" style="width: 120px;">Status</th>
                        <th class="text-center" style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($skus as $index => $row)
                        @php 
                            $firstDetail = $row->details->first(); 
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            
                            <td class="font-bold text-navy">
                                {{ $row->id_pengajuan ?? '-' }}
                                @if($row->details->count() > 1)
                                    <br><small class="label label-info" style="font-size: 9px;">+{{ $row->details->count() - 1 }} Items</small>
                                @endif
                            </td>

                            <td>{{ $row->nama ?? '-' }}</td>
                            <td>{{$row->detail_department->name ?? '-'}}</td>

                            <td style="word-wrap: break-word; max-width: 200px;">
                                {{ $firstDetail->item_name ?? 'N/A' }}
                            </td>
                            <td style="word-wrap: break-word; max-width: 250px;">
                                <small>{{ $firstDetail->specification ?? '-' }}</small>
                            </td>
                            <td>{{ $firstDetail->product_code ?? '-' }}</td>
                            <td>{{ $firstDetail->sku ?? '-' }}</td>
                            <td class="text-center">{{ $firstDetail->qty ?? 0 }}</td>
                            <td>{{ $firstDetail->uom ?? '-' }}</td>
                            <td>
                                <span class="label label-default" style="display: inline-block; max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $firstDetail->category ?? '-' }}
                                </span>
                            </td>
                            
                            <td class="text-center">
                                @if($firstDetail && $firstDetail->lampiran_foto)
                                    <a href="{{ asset('storage/' . $firstDetail->lampiran_foto) }}" target="_blank">
                                        <i class="fa fa-paperclip fa-lg text-success"></i>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td class="text-center">{{ $firstDetail->usage ?? 0 }}</td>
                            <td style="word-wrap: break-word; max-width: 150px;">
                                {{ Str::limit($firstDetail->keperluan ?? '-', 50) }}
                            </td>
                            <td>{{ $firstDetail->due_date ? date('d/m/Y', strtotime($firstDetail->due_date)) : '-' }}</td>
                            
                            <td class="text-center">
                                @if($row->status == 4)
                                    <span class="label label-danger">REJECTED</span>
                                @elseif($row->status == 6)
                                    <span class="label label-primary">COMPLETED</span>
                                @elseif($row->status == 7)
                                    <span class="label label-danger" style="background-color: #000;">FINAL REJECTED</span>
                                @else
                                    <span class="label label-warning">PENDING...</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="btn-group" style="display: flex; flex-wrap: nowrap;">
                                    <a href="{{ route('sku.show', $row->id) }}" class="btn btn-xs btn-white" style="flex-shrink: 0;">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                    @if($row->status == 4 && auth()->user()->npk == $row->npk)
                                        <a href="{{ route('sku.edit', $row->id) }}" class="btn btn-xs btn-warning" style="flex-shrink: 0; margin-left: 2px;">
                                            <i class="fa fa-edit"></i> Revise
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-responsive {
        border: 1px solid #e7eaec;
        margin-bottom: 20px;
    }

    /* Styling untuk tabel */
    #sku-table {
        border-collapse: separate;
        border-spacing: 0;
    }

    #sku-table thead th {
        background-color: #f3f3f4;
        text-transform: uppercase;
        font-size: 11px;
        white-space: nowrap;
        vertical-align: middle !important;
        border-bottom: 2px solid #e7eaec;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    #sku-table tbody td {
        vertical-align: middle !important;
        font-size: 12px;
    }

    /* Alternating row colors */
    #sku-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    #sku-table tbody tr:hover {
        background-color: #f5f5f5;
        cursor: pointer;
    }

    /* Kolom dengan konten panjang */
    #sku-table td:nth-child(5), /* Item */
    #sku-table td:nth-child(6), /* Specification */
    #sku-table td:nth-child(14) /* Purpose */ {
        word-wrap: break-word;
        word-break: break-word;
        white-space: normal !important;
    }

    /* Kolom action tetap rapi */
    #sku-table td:last-child {
        white-space: nowrap !important;
        width: 1%;
    }

    /* Tombol dalam group */
    .btn-group .btn {
        float: none !important;
        display: inline-block !important;
        margin-bottom: 0 !important;
        padding: 3px 8px !important;
    }

    /* Label untuk status */
    .label {
        padding: 3px 8px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 3px;
    }

    /* Footer pagination */
    .footer-dt-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        background: #f9f9f9;
        border-top: 1px solid #e7eaec;
        margin-top: -1px;
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .footer-dt-container {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }
        
        .dataTables_paginate {
            align-self: flex-start;
        }
        
        .dataTables_paginate .paginate_button {
            padding: 4px 8px !important;
            margin-left: 1px !important;
            font-size: 12px;
        }
    }

    /* Scroll bar styling */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
        width: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable dengan konfigurasi yang lebih fleksibel
    var table = $('#sku-table').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        responsive: true,
        autoWidth: false, // Nonaktifkan autoWidth untuk kontrol manual
        scrollX: true, // Enable horizontal scroll
        scrollCollapse: true,
        dom: '<"row"<"col-sm-12"f>>t<"footer-dt-container"<"col-sm-6"i><"col-sm-6"lp>>',
        language: {
            search: "Search:",
            searchPlaceholder: "Type to search...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            paginate: {
                next: "<i class='fa fa-chevron-right'></i>",
                previous: "<i class='fa fa-chevron-left'></i>"
            }
        },
        columnDefs: [
            {
                targets: [5, 6, 7, 13], // Kolom: Specification, Product Code, SKU, Purpose
                render: function(data, type, row) {
                    // Untuk display, gunakan ellipsis jika terlalu panjang
                    if (type === 'display' && data.length > 50) {
                        return '<span title="' + data + '">' + data.substr(0, 50) + '...</span>';
                    }
                    return data;
                }
            }
        ],
        buttons: [
            {
                extend: 'excel',
                title: 'SKU MONITORING REPORT', 
                filename: 'SKU_Report_' + new Date().toISOString().slice(0,10), 
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11] 
                },
                customize: function (xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('col:eq(1)', sheet).attr('width', 30);
                    $('col:eq(2)', sheet).attr('width', 40);
                    $('row c[r^="A1"]', sheet).attr('s', '22');
                    $('row c', sheet).attr('s', '25');
                }
            }
        ],
        initComplete: function() {
            // Adjust column widths after initialization
            this.api().columns.adjust();
        }
    });

    // CLICKABLE ROW - hanya jika bukan klik tombol/action
    $('#sku-table tbody').on('click', 'tr', function(e) {
        // Skip jika klik pada elemen yang bisa diklik (link, button, label, dll)
        if ($(e.target).closest('a, button, .label, .btn, [onclick]').length > 0) {
            return;
        }
        
        var viewUrl = $(this).find('td:last-child a').attr('href');
        if (viewUrl) {
            window.location.href = viewUrl;
        }
    });

    // Adjust table on window resize
    $(window).on('resize', function() {
        table.columns.adjust();
    });
});
</script>
@endpush