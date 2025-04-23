<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            'nipd' => 'required|unique:students,nipd|numeric',
            'name' => 'required',
            'class_id' => 'required|exists:classrooms,id',
            'gender' => 'required',
            'religion' => 'required',
            'pob' => 'required',
            'dob' => 'required|date',
            'address' => 'required',
            'residence_type' => 'required',
            'photo' => 'required|image|max:3024',
            'email' => 'required|email|unique:users,email'
        ];
    }

    public function messages()
    {
        return [
            'pob.required' => 'Tempat Lahir Wajib Diisi',
            'dob.required' => 'Tanggal Lahir Wajib Diisi',
        ];
    }
}
