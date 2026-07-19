<tr class="item-row">
    <td class="text-center row-number">{{ $index + 1 }}</td>
    
    {{-- Item Name --}}
    <td>
        <input type="text" name="details[{{ $index }}][item]" class="form-input autocomplete-item" 
               placeholder="Enter item..." value="{{ $detail->item_name ?? '' }}" autocomplete="off" required>
    </td>
    
    {{-- Specification --}}
    <td>
        <input type="text" name="details[{{ $index }}][spec]" class="form-input" 
               placeholder="Enter specs..." value="{{ $detail->specification ?? '' }}">
    </td>
    
    {{-- Qty --}}
    <td>
        <input type="number" name="details[{{ $index }}][qty]" class="form-input text-center" 
               placeholder="0" value="{{ $detail->qty ?? '' }}" required>
    </td>
    
    {{-- UOM (Select2) --}}
    <td>
        <select name="details[{{ $index }}][uom]" class="form-input select2-basic" required>
            <option value="">-- Select --</option>
            @foreach(\App\Models\SKUDetail::getUoms() as $key => $label)
                <option value="{{ $key }}" {{ (isset($detail) && $detail->uom == $key) ? 'selected' : '' }}>
                    {{ $key }} - {{ $label }}
                </option>
            @endforeach
        </select>
    </td>

    {{-- Category (Select2) --}}
    <td>
        <select name="details[{{ $index }}][category]" class="form-input select2-basic" required>
            <option value="">-- Select --</option>
            @foreach(\App\Models\SKUDetail::getCategories() as $key => $label)
                <option value="{{ $key }}" {{ (isset($detail) && $detail->category == $key) ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </td>
    
    {{-- Usage --}}
    <td>
        <input type="number" name="details[{{ $index }}][usage]" class="form-input text-center" 
               placeholder="0" value="{{ $detail->usage ?? '' }}">
    </td>
    
    {{-- File Attachment --}}
    <td>
        <input type="file" name="details[{{ $index }}][lampiran_foto]" class="form-input file-input" 
               accept=".jpg,.pdf">
        <div class="preview-container">
            {{-- Logic view existing file jika edit mode bisa ditaruh disini --}}
        </div>
        <small class="text-muted" style="display:block; font-size:9px;">JPG & PDF (Max 2MB)</small>
    </td>
    
    {{-- Purpose --}}
    <td>
        <input type="text" name="details[{{ $index }}][keperluan]" class="form-input" 
               placeholder="Purpose..." value="{{ $detail->keperluan ?? '' }}">
    </td>
    
    {{-- Due Date --}}
    <td>
        <input type="date" name="details[{{ $index }}][due_date]" class="form-input" 
               value="{{ $detail->due_date ?? '' }}">
    </td>
    
    {{-- Status --}}
    <td>
        <input type="text" name="details[{{ $index }}][status_item]" class="form-input" 
               readonly value="New">
    </td>
    
    {{-- Action --}}
    <td class="text-center">
        <button type="button" class="btn btn-danger btn-xs btn-remove-row"><i class="fa fa-times"></i></button>
    </td>
</tr>