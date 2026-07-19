{{-- Header Info Section --}}
<div class="row">
    {{-- Left Column --}}
    <div class="col-md-6">
        <div class="form-group row mb-3">
            <label class="col-sm-4 font-bold col-form-label">Full Name</label>
            <div class="col-sm-8">
                <input type="text" name="nama" class="form-control" value="{{ auth()->user()->name ?? 'Guest User' }}"
                    readonly>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-sm-4 font-bold col-form-label">NPK</label>
            <div class="col-sm-8">
                <input type="text" name="npk" class="form-control" value="{{ auth()->user()->npk ?? '000000' }}"
                    readonly>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-sm-4 font-bold col-form-label">Issue Date</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="issue_date" class="form-control" value="{{ date('d-M-Y') }}" readonly>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="col-md-6">
        <div class="form-group row mb-3">
            <label class="col-sm-4 font-bold col-form-label">Department</label>
            <div class="col-sm-8">
                {{-- Mengambil Nama Department dari relasi table departments --}}
                <input type="text" name="departement" class="form-control"
                    value="{{ auth()->user()->department->name ?? '-' }}" readonly>
                {{-- Hidden input untuk menyimpan ID jika diperlukan backend --}}
                <input type="hidden" name="dept_id" value="{{ auth()->user()->dept_id }}">
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-sm-4 font-bold col-form-label">Section</label>
            <div class="col-sm-8">
                {{-- Ubah dari input text ke readonly yang mengambil dari relasi --}}
                <input type="text" class="form-control" 
                    value="{{ auth()->user()->detail_department->name ?? '-' }}" readonly>
                <input type="hidden" name="detail_dept_id" value="{{ auth()->user()->detail_dept_id }}">
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-sm-4 font-bold col-form-label">Remarks</label>
            <div class="col-sm-8">
                <textarea name="remarks" class="form-control" rows="2"
                    placeholder="Reason for submission or additional notes..."></textarea>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-info mt-3">
    <i class="fa fa-info-circle"></i> <strong>Instructions:</strong> Fill in the columns below for each item. Click the 
    <strong>(+)</strong> button at the top right of the table to add a new row.
</div>

<hr>

{{-- Details Table Section --}}
<div class="table-responsive" style="overflow-x: auto !important; overflow-y: visible !important;">
    <table class="table table-bordered table-hover" id="skuTable" style="min-width: 1400px; font-size: 11px;">
        <thead>
            <tr>
                <th class="row-number" width="40">No</th>
                <th width="220">Item Name</th>
                <th width="200">Specification</th>
                <th class="col-qty" width="70">Qty</th>
                <th class="col-uom" width="120">UOM</th>
                <th width="150">Category</th>
                <th width="90">Usage / Month</th>
                <th width="180">File Attachment</th>
                <th width="200">Purpose</th>
                <th class="col-date" width="130">Due Date</th>
                <th width="80">Status</th>
                <th width="50">
                    <button type="button" class="btn btn-warning btn-xs" id="add-row">
                        <i class="fa fa-plus"></i>
                    </button>
                </th>
            </tr>
        </thead>
        <tbody id="table-body">
            @if(isset($sku))
                @foreach($sku->details as $index => $detail)
                    @include('sku.partials.item_row', ['index' => $index, 'detail' => $detail])
                @endforeach
            @else
                @include('sku.partials.item_row', ['index' => 0])
            @endif
        </tbody>
    </table>
</div>