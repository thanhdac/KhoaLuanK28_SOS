<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoiCuuHoRequest extends FormRequest
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
            'ten_co' => 'required|string|max:255',
            'khu_vuc_quan_ly' => 'required|string|max:255',
            'so_dien_thoai_hotline' => 'required|digits:10|unique:doi_cuu_ho,so_dien_thoai_hotline',
            'vi_tri_lat' => 'required|numeric',
            'vi_tri_lng' => 'required|numeric',
            'trang_thai' => 'required|string|max:30',
            'mo_ta' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'ten_co.required' => 'Tên đội không được để trống',
            'ten_co.max' => 'Tên đội không được quá 255 ký tự',
            'khu_vuc_quan_ly.required' => 'Khu vực quản lý không được để trống',
            'khu_vuc_quan_ly.max' => 'Khu vực quản lý không được quá 255 ký tự',
            'so_dien_thoai_hotline.required' => 'Số điện thoại hotline không được để trống',
            'so_dien_thoai_hotline.digits' => 'Số điện thoại hotline phải có 10 chữ số',
            'so_dien_thoai_hotline.unique' => 'Số điện thoại hotline đã tồn tại',
            'vi_tri_lat.required' => 'Vị trí latitude không được để trống',
            'vi_tri_lat.numeric' => 'Vị trí latitude phải là số',
            'vi_tri_lng.required' => 'Vị trí longitude không được để trống',
            'vi_tri_lng.numeric' => 'Vị trí longitude phải là số',
            'trang_thai.required' => 'Trạng thái không được để trống',
            'trang_thai.string' => 'Trạng thái phải là chuỗi',
        ];
    }
}
