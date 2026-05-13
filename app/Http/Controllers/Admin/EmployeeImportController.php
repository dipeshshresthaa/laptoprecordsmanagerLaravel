<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EmployeeTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\EmployeeImport;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            // This single line triggers the chunks, mapping, validation, and database batch inserts!
            Excel::import(new EmployeeImport, $request->file('excel_file'));

            return redirect()->route('admin.employees.import')
                ->with('success', 'Successfully imported employees from the spreadsheet.');

        } catch (ValidationException $e) {
            // Bulletproof error handling: If validation fails in the Import class,
            // tell the user EXACTLY which row and column failed.
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                // $failure->row() gives the exact row number in Excel
                // $failure->errors() gives the validation message
                $errorMessages[] = "Row {$failure->row()}: ".implode(', ', $failure->errors());
            }

            return back()->with('error', 'Validation failed in the Excel file:<br>'.implode('<br>', $errorMessages));

        } catch (\Exception $e) {
            // Catch any other general system errors
            return back()->with('error', 'A system error occurred during import: '.$e->getMessage());
        }
    }
}
