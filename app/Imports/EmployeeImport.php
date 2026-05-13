<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EmployeeImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            // Target the very first sheet (Index 0) for data entry.
            // The instructions sheet (Index 1) will be safely ignored.
            0 => new EmployeeDataImport,
        ];
    }
}
