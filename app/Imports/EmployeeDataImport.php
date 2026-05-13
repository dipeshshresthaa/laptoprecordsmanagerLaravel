<?php

namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeeDataImport implements ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow, WithValidation
{
    /**
     * Map the array to the Model.
     * Because of WithHeadingRow, $row uses the literal column names from row 1!
     */
    public function model(array $row)
    {
        // Skip completely empty rows
        if (! isset($row['emp_code']) || empty(trim($row['emp_code']))) {
            return null;
        }

        return new Employee([
            'emp_code' => trim($row['emp_code']),
            'first_name' => trim($row['first_name'] ?? ''),
            'middle_name' => trim($row['middle_name'] ?? ''),
            'last_name' => trim($row['last_name'] ?? ''),
            'phone_number' => trim($row['phone_number'] ?? ''),
            'address_state' => trim($row['address_state'] ?? ''),
            'address_district' => trim($row['address_district'] ?? ''),
            'address_municipality' => trim($row['address_municipality'] ?? ''),
            'pan_number' => trim($row['pan_number'] ?? ''),
            'role' => trim($row['role'] ?? 'Other'),
            'designation' => trim($row['designation'] ?? ''),
            'joining_date' => $this->parseDate($row['joining_date'] ?? null),
            'exit_date' => $this->parseDate($row['exit_date'] ?? null),
            'exit_reason' => trim($row['exit_reason'] ?? ''),
            'articleship_completion_date' => $this->parseDate($row['articleship_completion_date'] ?? null),
            'bank_name' => trim($row['bank_name'] ?? ''),
            'bank_branch' => trim($row['bank_branch'] ?? ''),
            'bank_account_number' => trim($row['bank_account_number'] ?? ''),
            'cit_number' => trim($row['cit_number'] ?? ''),
            'is_active' => (bool) trim($row['is_active'] ?? 1),
            'principal_id' => ! empty(trim($row['principal_id'] ?? '')) ? trim($row['principal_id']) : null,
            'created_by_id' => Auth::id(),
        ]);
    }

    /**
     * Validate data BEFORE inserting.
     * If validation fails, the controller catches the exact row and error.
     */
    public function rules(): array
    {
        return [
            '*.emp_code' => 'required|unique:employees,emp_code',
            '*.first_name' => 'required|string',
            '*.last_name' => 'required|string',
            '*.role' => 'required|in:Partner,ArticleTrainee,Other',
            '*.is_active' => 'required|boolean',
        ];
    }

    /**
     * Memory Optimization: Read 200 rows at a time into memory
     */
    public function chunkSize(): int
    {
        return 200;
    }

    /**
     * Memory Optimization: Insert 200 rows into the database in one single query
     */
    public function batchSize(): int
    {
        return 200;
    }

    /**
     * Helper to safely parse Excel dates
     */
    private function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }

        if (is_numeric($value)) {
            return Date::excelToDateTimeObject($value)->format('Y-m-d');
        }

        return date('Y-m-d', strtotime($value));
    }
}
