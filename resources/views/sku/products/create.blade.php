@extends('layouts.app-master')

@section('content')
<div class="row">
    <div class="col-lg-10 col-lg-offset-1">
        <div class="ibox shadow-sm border-bottom">
            <div class="ibox-title" style="border-top: 4px solid #1ab394;">
                <h5><i class="fa fa-plus text-navy"></i> ADD NEW MASTER PRODUCT</h5>
                <div class="ibox-tools">
                    <a href="{{ route('products.index') }}" class="btn btn-white btn-xs">
                        <i class="fa fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="ibox-content p-xl">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 border-right">
                            <h4 class="text-navy m-b-md">Product Identity</h4>
                            
                            <div class="form-group">
                                <label class="font-bold">Part Number <span class="text-danger">*</span></label>
                                <input type="text" name="product_code" value="{{ old('product_code') }}" 
                                    class="form-control {{ $errors->has('product_code') ? 'is-invalid' : '' }}" 
                                    placeholder="e.g., CO-L_OIL_001" required>
                                @error('product_code') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-bold">SKU Code</label>
                                <input type="text" name="sku_code" value="{{ old('sku_code') }}" 
                                    class="form-control" placeholder="Enter SKU if available">
                            </div>

                            <div class="form-group">
                                <label class="font-bold">Item Name <span class="text-danger">*</span></label>
                                <input type="text" name="item_name" value="{{ old('item_name') }}" 
                                    class="form-control" placeholder="Full item description" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h4 class="text-navy m-b-md">Classification</h4>
                            
                            <div class="form-group">
                                <label class="font-bold">UOM <span class="text-danger">*</span></label>
                                <select name="uom" class="form-control" required>
                                    <option value="">- Select UOM -</option>
                                    @foreach(\App\Models\Product::UOMS as $key => $value)
                                        <option value="{{ $key }}" {{ old('uom') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="font-bold">Category <span class="text-danger">*</span></label>
                                <select name="category" class="form-control" required>
                                    @foreach(\App\Models\Product::CATEGORIES as $key => $value)
                                        <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="font-bold">Specification</label>
                                <textarea name="specification" class="form-control" rows="3" 
                                    placeholder="Enter technical specifications here...">{{ old('specification') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="font-bold">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    @foreach(\App\Models\Product::STATUSES as $key => $value)
                                        <option value="{{ $key }}" {{ old('status', 'active') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <h4 class="text-navy m-b-md">Inventory Settings <span class="small text-muted">(Optional)</span></h4>
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label class="font-bold">Usage/Month</label>
                            <input type="number" step="any" name="usage_month" value="{{ old('usage_month') }}" class="form-control" placeholder="Usage">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-bold">MOQ</label>
                            <input type="number" step="any" name="moq" value="{{ old('moq') }}" class="form-control" placeholder="MOQ">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-bold">LOT</label>
                            <input type="number" step="any" name="lot" value="{{ old('lot') }}" class="form-control" placeholder="LOT">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-bold">MIN</label>
                            <input type="number" step="any" name="min" value="{{ old('min') }}" class="form-control" placeholder="MIN">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-bold">ROP</label>
                            <input type="number" step="any" name="rop" value="{{ old('rop') }}" class="form-control" placeholder="ROP">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-bold">MAX</label>
                            <input type="number" step="any" name="max" value="{{ old('max') }}" class="form-control" placeholder="MAX">
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="form-group row">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-white m-r-sm" type="reset">Reset Form</button>
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-save"></i> Save Product
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .border-right { border-right: 1px solid #e7eaec; }
    .p-xl { padding: 40px; }
    .is-invalid { border-color: #ed5565 !important; }
</style>
@endpush