<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChucVuRequest extends FormRequest
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
            'ten_chuc_vu' => 'required|string|max:255|unique:chuc_vu,ten_chuc_vu',
            'slug_chuc_vu' => 'required|string|max:255|unique:chuc_vu,slug_chuc_vu',
            'mo_ta' => 'nullable|string',
            'tinh_trang' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'ten_chuc_vu.required' => 'Tên chức vụ không được để trống',
            'ten_chuc_vu.max' => 'Tên chức vụ không được quá 255 ký tự',
            'ten_chuc_vu.unique' => 'Tên chức vụ đã tồn tại',
            'slug_chuc_vu.required' => 'Slug chức vụ không được để trống',
            'slug_chuc_vu.max' => 'Slug chức vụ không được quá 255 ký tự',
            'slug_chuc_vu.unique' => 'Slug chức vụ đã tồn tại',
            'tinh_trang.required' => 'Tình trạng không được để trống',
            'tinh_trang.integer' => 'Tình trạng phải là số nguyên',
        ];
    }
}
