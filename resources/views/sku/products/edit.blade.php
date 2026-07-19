@extends('layouts.app-master')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="ibox shadow-sm">
            <div class="ibox-title" style="border-top: 4px solid #f8ac59;">
                <h5><i class="fa fa-pencil text-warning"></i> Edit Product: {{ $product->product_code }}</h5>
                <div class="ibox-tools">
                    <a href="{{ route('products.index') }}" class="btn btn-white btn-xs">
                        <i class="fa fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="font-bold">Part Number</label>
                            <input type="text" name="product_code" class="form-control" value="{{ old('product_code', $product->product_code) }}" required>
                            @error('product_code') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-bold">SKU Code</label>
                            <input type="text" name="sku_code" class="form-control" value="{{ old('sku_code', $product->sku_code) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-bold">Item Name</label>
                        <input type="text" name="item_name" class="form-control" value="{{ old('item_name', $product->item_name) }}" required>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="font-bold">UOM</label>
                            <select name="uom" class="form-control" required>
                                @foreach(\App\Models\Product::UOMS as $key => $value)
                                    <option value="{{ $key }}" {{ old('uom', $product->uom) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-bold">Category</label>
                            <select name="category" class="form-control" required>
                                @foreach(\App\Models\Product::CATEGORIES as $key => $value)
                                    <option value="{{ $key }}" {{ old('category', $product->category) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="font-bold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            @foreach(\App\Models\Product::STATUSES as $key => $value)
                                <option value="{{ $key }}" {{ old('status', $product->status) == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-bold">Specification</label>
                        <textarea name="specification" class="form-control" rows="3">{{ old('specification', $product->specification) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Product Image (JPG Only)</label>
                        
                        {{-- Tampilkan Preview jika sudah ada gambar --}}
                        @if($product->product_image)
                            <div class="m-b-sm">
                                <a href="javascript:void(0);" onclick="showImage('{{ asset('storage/' . $product->product_image) }}')" title="Click to Zoom">
                                <img src="{{ asset('storage/' . $product->product_image) }}" alt="Product Image" style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                                </a>
                                <br>
                                <small class="text-muted">Current image. Upload new file to replace.</small>
                            </div>
                        @else
                            <div class="alert alert-warning m-b-sm">
                                <i class="fa fa-exclamation-circle"></i> No image available. Please upload one.
                            </div>
                        @endif

                        <input type="file" name="product_image" class="form-control" accept="image/jpg">
                        <span class="help-block m-b-none">Format: JPG Max: 2MB. (PDF is not allowed)</span>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <h4 class="text-navy m-b-md">Inventory Settings <span class="small text-muted">(Optional)</span></h4>
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label class="font-bold">Usage/Month</label>
                            <input type="number" step="any" name="usage_month" value="{{ old('usage_month', $product->usage_month) }}" class="form-control" placeholder="Usage">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-bold">MOQ</label>
                            <input type="number" step="any" name="moq" value="{{ old('moq', $product->moq) }}" class="form-control" placeholder="MOQ">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-bold">LOT</label>
                            <input type="number" step="any" name="lot" value="{{ old('lot', $product->lot) }}" class="form-control" placeholder="LOT">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-bold">MIN</label>
                            <input type="number" step="any" name="min" value="{{ old('min', $product->min) }}" class="form-control" placeholder="MIN">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-bold">ROP</label>
                            <input type="number" step="any" name="rop" value="{{ old('rop', $product->rop) }}" class="form-control" placeholder="ROP">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-bold">MAX</label>
                            <input type="number" step="any" name="max" value="{{ old('max', $product->max) }}" class="form-control" placeholder="MAX">
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('products.index') }}" class="btn btn-white">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="ibox shadow-sm border-bottom-danger">
            <div class="ibox-content">
                <p class="small text-muted">Deleting this product will permanently remove it from the Master List. This action cannot be undone.</p>
                <form id="delete-product-form" action="{{ route('products.destroy', $product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" id="btn-delete-product" class="btn btn-danger btn-block btn-outline">
                        <i class="fa fa-trash"></i> Delete Product
                    </button>
                </form>
            </div>
        </div>

        <div class="ibox shadow-sm">
            <div class="ibox-title">
                <h5>Product Info</h5>
            </div>
            <div class="ibox-content">
                <ul class="list-unstyled">
                    <li><strong>Source:</strong> <span class="label label-{{ $product->input_source == 'import' ? 'primary' : 'info' }}">{{ strtoupper($product->input_source) }}</span></li>
                    <li class="m-t-xs"><strong>Date Added:</strong> {{ $product->created_at->format('M d, Y') }}</li>
                    <li class="m-t-xs"><strong>Last Update:</strong> {{ $product->updated_at->diffForHumans() }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- --- MODAL IMAGE PREVIEW (RAPID & CLEAN) --- --}}
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated fadeIn">
            {{-- Header Flexbox: Judul Tengah, Close Kanan --}}
            <div class="modal-header d-flex justify-content-between align-items-center" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e6e7; padding: 15px 20px;">
                <h4 class="modal-title m-0" style="font-weight: bold; flex-grow: 1; text-align: center;">Product Image Preview</h4>
                <button type="button" class="close m-0 p-0" data-dismiss="modal" style="font-size: 28px; line-height: 1; opacity: 0.6;">&times;</button>
            </div>
            
            {{-- Body: Background Abu & Gambar Shadow --}}
            <div class="modal-body text-center" style="background-color: #f3f3f4; padding: 30px;">
                <img id="modalImageSrc" src="" class="img-fluid shadow-lg" style="max-height: 80vh; max-width: 100%; border-radius: 4px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Styling khusus tombol close modal biar rapi */
    .modal-header .close:hover {
        opacity: 1;
        color: #ed5565; /* Warna merah saat hover */
        transition: 0.2s;
    }
</style>
@endpush

@push('scripts')
<script>
    // Fungsi untuk memanggil Modal
    function showImage(src) {
        // 1. Set source gambar di dalam modal
        $('#modalImageSrc').attr('src', src);
        // 2. Tampilkan modal
        $('#imageModal').modal('show');
    }

    // SweetAlert2 Delete Confirmation
    $('#btn-delete-product').on('click', function(e) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Produk ini akan dihapus secara permanen dari sistem!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ed5565',
            cancelButtonColor: '#c2c2c2',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#delete-product-form').submit();
            }
        });
    });
</script>
@endpush