<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Access the employee ID to apply unique validation rule
        $employeeId = $this->route('employee');
        // dd($employeeId);

        return [
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|email|unique:employees,email,' . $employeeId,
            'phone'         => 'required|string|max:20',
            // 'image'         => 'image|mimes:jpeg,png,jpg,gif',
            'position'      => 'required|string|max:100',
            'department'    => 'required|string|max:100',
            'joining_date'  => 'required|date',
            'salary'        => 'required|numeric|min:0',
            'address'       => 'required|string|max:255',
            'city'          => 'required|string|max:100',
            'state'         => 'required|string|max:100',
            'zip'           => 'required|string|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name  required.',
            'first_name.string' => 'The first name must be a string.',
            'first_name.max' => 'Only 255 letter er besi dite parben na.',
        ];
    }
}
