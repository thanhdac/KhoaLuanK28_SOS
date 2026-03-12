<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoaiSuCoRequest extends FormRequest
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
            'ten_danh_muc' => 'required|string|max:255',
            'slug_danh_muc' => 'required|string|unique:loai_su_co,slug_danh_muc|max:255',
            'mo_ta' => 'nullable|string',
            'trang_thai' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'ten_danh_muc.required' => 'Tên danh mục không được để trống',
            'ten_danh_muc.max' => 'Tên danh mục không được quá 255 ký tự',
            'slug_danh_muc.required' => 'Slug danh mục không được để trống',
            'slug_danh_muc.unique' => 'Slug danh mục đã tồn tại',
            'slug_danh_muc.max' => 'Slug danh mục không được quá 255 ký tự',
            'mo_ta.string' => 'Mô tả phải là chuỗi ký tự',
            'trang_thai.required' => 'Trạng thái không được để trống',
            'trang_thai.boolean' => 'Trạng thái phải là boolean',
        ];
    }
}
