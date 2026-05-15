<?php

namespace App\Exports;

use App\Models\SystemLookup;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeeTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new EmployeeDataSheet,
            new EmployeeInstructionsSheet,
            new BankLookupsSheet, // Added the new lookups sheet
        ];
    }
}

class EmployeeDataSheet implements FromArray, WithHeadings, WithStyles, WithTitle
{
    public function headings(): array
    {
        return [
            'id', 'emp_code', 'first_name', 'middle_name', 'last_name', 'phone_number',
            'address_state', 'address_district', 'address_municipality', 'pan_number',
            'role', 'designation', 'joining_date', 'exit_date', 'exit_reason',
            'articleship_completion_date', 'bank_name_id', 'bank_branch_id', // Changed to _id
            'bank_account_number', 'cit_number', 'is_active', 'principal_id',
        ];
    }

    public function array(): array
    {
        // Provide one sample row to guide the user (using integer IDs for banks)
        return [
            [
                '', // Leave ID blank for auto-generation
                'EMP-001', 'Ram', 'Bahadur', 'Thapa', '9841000000',
                'Bagmati', 'Kathmandu', 'KMC-10', '123456789',
                'ArticleTrainee', 'Audit Assistant', '2024-01-15', '', '',
                '2027-01-14', '1', '2', // Sample IDs for Bank and Branch
                '00112233445566', 'CIT-98765', '1', '2',
            ],
        ];
    }

    public function title(): string
    {
        return 'Data Entry';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF1D4ED8']]],
        ];
    }
}

class EmployeeInstructionsSheet implements FromArray, WithHeadings, WithStyles, WithTitle
{
    public function headings(): array
    {
        return ['Column Name', 'Required?', 'Format / Rules / Accepted Values'];
    }

    public function array(): array
    {
        return [
            ['id', 'No', 'Leave BLANK to auto-generate, or provide an 8-char unique string.'],
            ['emp_code', 'Yes', 'Must be unique (e.g., EMP-001).'],
            ['first_name', 'Yes', 'Text.'],
            ['middle_name', 'No', 'Text.'],
            ['last_name', 'Yes', 'Text.'],
            ['phone_number', 'Yes', '10-digit mobile number (e.g., 98XXXXXXXX).'],
            ['address_state', 'Yes', 'Province Name (e.g., Bagmati, Koshi, Gandaki).'],
            ['address_district', 'Yes', 'District Name (e.g., Kathmandu, Lalitpur).'],
            ['address_municipality', 'Yes', 'Municipality/VDC Name.'],
            ['pan_number', 'No', '9-digit Nepal PAN number.'],
            ['role', 'Yes', 'MUST BE EXACTLY ONE OF: "Partner", "ArticleTrainee", or "Other".'],
            ['designation', 'Yes', 'Job Title (e.g., Audit Manager, Trainee, Tax Consultant).'],
            ['joining_date', 'Yes', 'Format: YYYY-MM-DD (Gregorian Date).'],
            ['exit_date', 'No', 'Format: YYYY-MM-DD. Leave blank if currently employed.'],
            ['exit_reason', 'No', 'Text reason if exit_date is provided.'],
            ['articleship_completion_date', 'No', 'Format: YYYY-MM-DD. Required if role is ArticleTrainee.'],
            ['bank_name_id', 'No', 'MUST be an ID from the "Bank & Branch IDs" sheet.'],
            ['bank_branch_id', 'No', 'MUST be an ID from the "Bank & Branch IDs" sheet.'],
            ['bank_account_number', 'No', 'Exact account number.'],
            ['cit_number', 'No', 'Citizen Investment Trust number if applicable.'],
            ['is_active', 'Yes', 'MUST BE 1 (Active) or 0 (Left/Inactive).'],
            ['principal_id', 'No', 'The Database ID of the Partner they report to (Required for Trainees).'],
        ];
    }

    public function title(): string
    {
        return 'Instructions & Rules';
    }

    public function styles(Worksheet $sheet)
    {
        // Make headers bold, auto-size columns
        foreach (range('A', 'C') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFDC2626']]],
        ];
    }
}

class BankLookupsSheet implements FromArray, WithHeadings, WithStyles, WithTitle
{
    public function headings(): array
    {
        return ['Lookup Type', 'Database ID (Use This)', 'Actual Name'];
    }

    public function array(): array
    {
        $lookups = SystemLookup::whereIn('category', ['BankName', 'BankBranch'])
            ->orderBy('category')
            ->orderBy('value')
            ->get();

        $rows = [];

        if ($lookups->isEmpty()) {
            // Notification inside the excel sheet if no banks exist yet
            $rows[] = ['WARNING', 'N/A', 'No Banks or Branches found! Please add them in the System Settings before importing.'];
        } else {
            foreach ($lookups as $lookup) {
                // E.g., ['BankName', '14', 'Nabil Bank']
                $rows[] = [
                    $lookup->category === 'BankName' ? 'Bank Name' : 'Bank Branch',
                    $lookup->id,
                    $lookup->value,
                ];
            }
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Bank & Branch IDs';
    }

    public function styles(Worksheet $sheet)
    {
        foreach (range('A', 'C') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF059669']]], // Emerald Green header
        ];
    }
}
