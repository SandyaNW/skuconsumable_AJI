<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\NewProductPortalFormEvent;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NppFormEventImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {

        return new NewProductPortalFormEvent([
            'milestone' => $row['milestone'],
            'event' => $row['event'],
            'form_type' => $row['form_type'],
            'task_no' => $row['task_no'],
            'task' => $row['task'],
        ]);
    }
}
