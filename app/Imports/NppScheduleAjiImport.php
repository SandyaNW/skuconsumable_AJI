<?php

namespace App\Imports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\NewProductPortalScheduleAji;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Date;

class NppScheduleAjiImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        try {
            return new NewProductPortalScheduleAji([
                'milestone' => $row['milestone'],
                'event' => $row['event'],
                'detail_description' => $row['detail_description'],
                'pic' => $row['pic'],
                'urgent' => $row['urgensi'],
                'plan_start' => date("Y-m-d", strtotime($row['plan_start'])),
                'plan_end' => date("Y-m-d", strtotime($row['plan_end'])),
                'koordinasi' => $row['koordinasi'],
                'total_checklist' => $row['total_checklist'],
                'excel_link' => $row['excel_link'],
                'calendar' => $row['calendar'],
            ]);
        } catch (\Throwable $th) {
            return redirect('/NewProductPortal/schedule_upload_aji_internal')->with('fail', "Import Failed!".$th->getMessage());
        }
    }
}
