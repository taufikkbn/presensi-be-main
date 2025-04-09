<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentUpdateRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'nipdEdit' => 'required|numeric|unique:students,nipd,' . $this->student->nipd . ',nipd',
            'nameEdit' => 'required',
            'class_idEdit' => 'required|exists:classrooms,id',
            'genderEdit' => 'required',
            'religionEdit' => 'required',
            'pobEdit' => 'required',
            'dobEdit' => 'required|date',
            'addressEdit' => 'required',
            'residence_typeEdit' => 'required',
            'photoEdit' => 'nullable|image|max:3024',
            'emailEdit' => 'email|unique:users,email,' . $this->student->user_id . ',id',
        ];
    }
}
