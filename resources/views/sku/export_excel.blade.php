<table>
    <thead>
        <tr>
            <th style="background-color: #f3f3f4; border: 1px solid #000; font-weight: bold; text-align: center;">NO</th>
            {{-- Tambahan Kolom ID SKU --}}
            <th style="background-color: #f3f3f4; border: 1px solid #000; font-weight: bold; text-align: center;">ID SUBMISSION</th>
            <th style="background-color: #f3f3f4; border: 1px solid #000; font-weight: bold; text-align: center;">DATE</th>
            <th style="background-color: #f3f3f4; border: 1px solid #000; font-weight: bold; text-align: center;">REQUESTOR NAME</th>
            <th style="background-color: #f3f3f4; border: 1px solid #000; font-weight: bold; text-align: center;">NPK</th>
            <th style="background-color: #f3f3f4; border: 1px solid #000; font-weight: bold; text-align: center;">DEPARTMENT</th>
            <th style="background-color: #f3f3f4; border: 1px solid #000; font-weight: bold; text-align: center;">SECTION</th>
            <th style="background-color: #f3f3f4; border: 1px solid #000; font-weight: bold; text-align: center;">ITEM NAME</th>
            <th style="background-color: #f3f3f4; border: 1px solid #000; font-weight: bold; text-align: center;">SPECIFICATION</th>
            <th style="background-color: #f3f3f4; border: 1px solid #000; font-weight: bold; text-align: center;">CATEGORY</th>
            <th style="background-color: #f3f3f4; border: 1px solid #000; font-weight: bold; text-align: center;">PART NUMBER</th>
            <th style="background-color: #f3f3f4; border: 1px solid #000; font-weight: bold; text-align: center;">SKU</th>
            {{-- QTY dan UOM Dipisah --}}
            <th style="background-color: #f3f3f4; border: 1px solid #000; font-weight: bold; text-align: center;">QTY</th>
            <th style="background-color: #f3f3f4; border: 1px solid #000; font-weight: bold; text-align: center;">UOM</th>
            <th style="background-color: #f3f3f4; border: 1px solid #000; font-weight: bold; text-align: center;">USAGE/MONTH</th>
            <th style="background-color: #f3f3f4; border: 1px solid #000; font-weight: bold; text-align: center;">PURPOSE</th>
            <th style="background-color: #f3f3f4; border: 1px solid #000; font-weight: bold; text-align: center;">STATUS</th>
            <th style="background-color: #f3f3f4; border: 1px solid #000; font-weight: bold; text-align: center;">REJECTION REASON</th>
        </tr>
    </thead>
    <tbody>
        @foreach($skus as $index => $sku)
            @php
                $detail = $sku->details->first(); 
                
                // Logika Warna (Sesuaikan code status dengan sistem kamu)
                $bgColor = '#ffffff';
                if($sku->status == 6) $bgColor = '#dff0d8'; // Completed (Hijau)
                if($sku->status == 4 || $sku->status == 7) $bgColor = '#f2dede'; // Rejected (Merah)
                if($sku->status == 1 || $sku->status == 2 || $sku->status == 5) $bgColor = '#fcf8e3'; // Pending/Process (Kuning)
            @endphp
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
                
                {{-- 1. Tambahan ID Submission --}}
                <td style="border: 1px solid #000;">{{ $sku->id_pengajuan ?? '-' }}</td>
                
                <td style="border: 1px solid #000;">{{ $sku->issue_date }}</td>
                <td style="border: 1px solid #000;">{{ $sku->nama }}</td>
                <td style="border: 1px solid #000;">{{ $sku->npk }}</td>
                <td style="border: 1px solid #000;">{{ $sku->departement }}</td>
                
                {{-- 2. Perbaikan Section (Ambil dari relasi detail_department) --}}
                <td style="border: 1px solid #000;">{{ $sku->detail_department->name ?? '-' }}</td>
                
                <td style="border: 1px solid #000;">{{ $detail->item_name ?? '-' }}</td>
                <td style="border: 1px solid #000;">{{ $detail->specification ?? '-' }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $detail->category ?? '-' }}</td>
                <td style="border: 1px solid #000;">{{ $detail->product_code ?? '-' }}</td>
                <td style="border: 1px solid #000;">{{ $detail->sku ?? '-' }}</td>
                
                {{-- 3. QTY dan UOM Dipisah (2 Kolom) --}}
                <td style="border: 1px solid #000; text-align: center;">{{ $detail->qty ?? 0 }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $detail->uom ?? '-' }}</td>
                
                <td style="border: 1px solid #000; text-align: center;">{{ $detail->usage ?? 0 }}</td>
                <td style="border: 1px solid #000;">{{ $detail->keperluan ?? '-' }}</td>
                
                <td style="border: 1px solid #000; background-color: {{ $bgColor }};">
                    @if($sku->status == 1) Pending Supervisor
                    @elseif($sku->status == 2) Pending Dept Head
                    @elseif($sku->status == 3) Process Finance
                    @elseif($sku->status == 4) Rejected
                    @elseif($sku->status == 5) Wait PPIC
                    @elseif($sku->status == 6) Completed
                    @elseif($sku->status == 7) Final Rejected
                    @else {{ $sku->status }}
                    @endif
                </td>
                <td style="border: 1px solid #000;">{{ $sku->reject_reason ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>