<?php

namespace App\Exports;

use App\Models\SKUSubmission;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SkuExport implements FromView, ShouldAutoSize, WithStyles
{
    
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function view(): View
        {
            $query = SKUSubmission::with('details');

            // Gunakan isset atau helper request biar gak error kalau filternya kosong
            if (!empty($this->filters->status)) {
                $query->where('status', $this->filters->status);
            }
            
            if (!empty($this->filters->start_date) && !empty($this->filters->end_date)) {
                $query->whereBetween('issue_date', [$this->filters->start_date, $this->filters->end_date]);
            }

            return view('sku.export_excel', [
                'skus' => $query->latest()->get()
            ]);
        }

    public function styles(Worksheet $sheet)
    {
        return [
            // Bikin baris 1 (Header) Bold
            1 => ['font' => ['bold' => true]],
        ];
    }
    
}