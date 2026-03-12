<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChucNangRequest extends FormRequest
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
            'ten_chuc_nang' => 'required|string|max:255|unique:chuc_nang,ten_chuc_nang',
        ];
    }

    public function messages()
    {
        return [
            'ten_chuc_nang.required' => 'Tên chức năng không được để trống',
            'ten_chuc_nang.max' => 'Tên chức năng không được quá 255 ký tự',
            'ten_chuc_nang.unique' => 'Tên chức năng đã tồn tại',
        ];
    }
}
