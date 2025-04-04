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
            'name' => 'required|string|max:100',
            'surname' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:employees,email',
            'phone' => 'string|max:15',
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        \App\Classes\ApiResponse::throwValidation($validator->errors());
    }

}
