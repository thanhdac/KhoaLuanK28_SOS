<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaiNguyenCuuHoRequest extends FormRequest
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
            'id_doi_cuu_ho' => 'required|exists:doi_cuu_ho,id_doi_cuu_ho',
            'ten_tai_nguyen' => 'required|string|max:255',
            'loai_tai_nguyen' => 'required|string|max:100',
            'so_luong' => 'required|integer|min:1',
            'trang_thai' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'id_doi_cuu_ho.required' => 'Đội cứu hộ không được để trống',
            'id_doi_cuu_ho.exists' => 'Đội cứu hộ không tồn tại',
            'ten_tai_nguyen.required' => 'Tên tài nguyên không được để trống',
            'ten_tai_nguyen.max' => 'Tên tài nguyên không được quá 255 ký tự',
            'loai_tai_nguyen.required' => 'Loại tài nguyên không được để trống',
            'loai_tai_nguyen.max' => 'Loại tài nguyên không được quá 100 ký tự',
            'so_luong.required' => 'Số lượng không được để trống',
            'so_luong.integer' => 'Số lượng phải là số nguyên',
            'so_luong.min' => 'Số lượng phải tối thiểu 1',
            'trang_thai.required' => 'Trạng thái không được để trống',
            'trang_thai.integer' => 'Trạng thái phải là số nguyên',
        ];
    }
}
