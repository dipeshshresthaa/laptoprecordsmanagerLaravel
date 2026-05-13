<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Assuming middleware handles auth/admin checks
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee') ? $this->route('employee')->id : null;
        $uniqueEmpRule = $employeeId ? "unique:employees,emp_code,{$employeeId},id" : 'unique:employees,emp_code';
        $uniqueAccountRule = $employeeId ? "unique:employees,bank_account_number,{$employeeId},id" : 'unique:employees,bank_account_number';

        return [
            'emp_code' => ['required', 'string', $uniqueEmpRule],
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'phone_number' => 'nullable|numeric',
            'address_state' => 'nullable|string',
            'address_district' => 'nullable|string',
            'address_municipality' => 'nullable|string',
            'pan_number' => 'nullable|digits:9',
            'designation' => 'nullable|string',
            'joining_date' => 'nullable|date|before_or_equal:9999-12-31',
            'bank_name' => 'nullable|string',
            'bank_branch' => 'nullable|string',
            'bank_account_number' => ['nullable', 'string', $uniqueAccountRule],
            'cit_number' => 'nullable|string',
            'role' => 'required|string',
            'principal_id' => 'required_if:role,ArticleTrainee',
            'articleship_deed' => 'nullable|file|mimes:pdf|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'principal_id.required_if' => 'An article trainee must have a principal assigned.',
            'bank_account_number.unique' => 'The bank account number is already in use by some employee. Confirm the bank account number again.',
        ];
    }
}
