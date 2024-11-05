<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
        return [
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|email|unique:employees,email',
            'phone'         => 'required|string|max:15',
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif',
            'position'      => 'required|string|max:255',
            'department'    => 'required|string|max:255',
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
            'first_name.required' => 'Please give a first name.',
            'first_name.string' => 'The first name must be a string.',
            'first_name.max' => 'Only 255 letter er besi dite parben na.',

            'last_name.required' => 'The last name is required.',
            'last_name.string' => 'The last name must be a string.',
            'last_name.max' => 'The last name may not exceed 255 characters.',
        ];
    }
}
