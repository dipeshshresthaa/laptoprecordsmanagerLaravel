<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EmployeeTemplateExport;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeeImportController extends Controller
{
    public function create()
    {
        return view('admin.employees.import');
    }

    public function createDocumentUpload()
    {
        return view('admin.employees.upload_documents');
    }

    public function storeDocuments(Request $request)
    {
        // Validate that they uploaded an array of files, and all are PDFs
        $request->validate([
            'documents' => 'required|array',
            'documents.*' => 'required|mimes:pdf|max:10240', // 10MB max per file
        ]);

        $matchedCount = 0;
        $unmatchedFiles = [];

        foreach ($request->file('documents') as $file) {
            $originalName = $file->getClientOriginalName();

            // Regex to check if filename exactly matches: EMP-001_Deed.pdf or EMP-001_Completion.pdf
            // It extracts "EMP-001" into $matches[1] and "Deed/Completion" into $matches[2]
            if (preg_match('/^([A-Z0-9-]+)_(Deed|Completion)\.pdf$/i', $originalName, $matches)) {
                $empCode = $matches[1];
                $type = strtolower($matches[2]);

                $employee = Employee::query()->where('emp_code', $empCode)->first();

                if ($employee) {
                    $docsDir = 'employee_documents';
                    $newFileName = $empCode.'_'.ucfirst($type).'_'.time().'.pdf';
                    $path = $file->storeAs($docsDir, $newFileName, 'public');

                    // If it's a Deed, replace old Deed
                    if ($type === 'deed') {
                        if ($employee->articleship_deed_path) {
                            Storage::disk('public')->delete($employee->articleship_deed_path);
                        }
                        $employee->articleship_deed_path = $path;
                    }
                    // If it's a Completion Cert, replace old Cert
                    else {
                        if ($employee->completion_certificate_path) {
                            Storage::disk('public')->delete($employee->completion_certificate_path);
                        }
                        $employee->completion_certificate_path = $path;
                    }

                    $employee->save();
                    $matchedCount++;
                } else {
                    $unmatchedFiles[] = "$originalName (Employee '$empCode' not found in database)";
                }
            } else {
                $unmatchedFiles[] = "$originalName (Invalid naming format)";
            }
        }

        // Build the return message
        if (count($unmatchedFiles) > 0) {
            $errorMsg = "Attached $matchedCount documents. Failed to match: ".implode(', ', $unmatchedFiles);

            return back()->with('error', $errorMsg);
        }

        return back()->with('success', "Successfully attached all $matchedCount documents to their respective employees!");
    }

    public function downloadTemplate()
    {
        return Excel::download(new EmployeeTemplateExport, 'Employee_Import_Template.xlsx');
    }

    public function store(Request $request)
    {
        // Notice we changed this to 'excel_file' and added xlsx support
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        $file = $request->file('excel_file');

        // Use Maatwebsite Excel to read the file directly into an array.
        // [0] targets the very first sheet ("Data Entry") ignoring the Instructions sheet
        $sheets = Excel::toArray(new \stdClass, $file);
        $rows = $sheets[0];

        $header = true;
        $count = 0;

        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                if ($header) {
                    $header = false;

                    continue; // Skip the header row
                }

                // Stop processing if the row is entirely empty (common at the bottom of Excel files)
                if (empty(array_filter($row))) {
                    continue;
                }

                // Helper to safely parse Excel dates (Excel stores dates as numbers behind the scenes)
                $parseDate = function ($value) {
                    if (empty($value)) {
                        return null;
                    }
                    if (is_numeric($value)) {
                        return Date::excelToDateTimeObject($value)->format('Y-m-d');
                    }

                    return date('Y-m-d', strtotime($value));
                };

                // Map all 21 columns exactly as they appear in the template
                Employee::create([
                    'emp_code' => trim($row[0] ?? ''),
                    'first_name' => trim($row[1] ?? ''),
                    'middle_name' => trim($row[2] ?? ''),
                    'last_name' => trim($row[3] ?? ''),
                    'phone_number' => trim($row[4] ?? ''),
                    'address_state' => trim($row[5] ?? ''),
                    'address_district' => trim($row[6] ?? ''),
                    'address_municipality' => trim($row[7] ?? ''),
                    'pan_number' => trim($row[8] ?? ''),
                    'role' => trim($row[9] ?? 'Other'),
                    'designation' => trim($row[10] ?? ''),
                    'joining_date' => $parseDate($row[11] ?? null),
                    'exit_date' => $parseDate($row[12] ?? null),
                    'exit_reason' => trim($row[13] ?? ''),
                    'articleship_completion_date' => $parseDate($row[14] ?? null),
                    'bank_name' => trim($row[15] ?? ''),
                    'bank_branch' => trim($row[16] ?? ''),
                    'bank_account_number' => trim($row[17] ?? ''),
                    'cit_number' => trim($row[18] ?? ''),
                    'is_active' => (bool) trim($row[19] ?? 1),
                    'principal_id' => ! empty(trim($row[20] ?? '')) ? trim($row[20]) : null,
                    'created_by_id' => Auth::user()->id,
                ]);
                $count++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            // This is amazing for debugging. If they upload a bad file, it tells them EXACTLY which row failed!
            return back()->with('error', 'Error processing row '.($index + 1).': '.$e->getMessage());
        }

        return redirect()->route('admin.employees.import')->with('success', "Successfully imported {$count} employees.");
    }
}
