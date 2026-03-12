<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YeuCauCuuHoRequest extends FormRequest
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
            'id_nguoi_dung' => 'required|exists:nguoi_dung,id_nguoi_dung',
            'id_loai_su_co' => 'required|exists:loai_su_co,id_loai_su_co',
            'vi_tri_lat' => 'required|numeric',
            'vi_tri_lng' => 'required|numeric',
            'vi_tri_dia_chi' => 'required|string|max:500',
            'chi_tiet' => 'nullable|string',
            'mo_ta' => 'nullable|string',
            'hinh_anh' => 'nullable|string',
            'so_nguoi_bi_anh_huong' => 'nullable|integer|min:1',
            'muc_do_khan_cap' => 'required|integer|min:1|max:5',
            'diem_uu_tien' => 'nullable|integer',
            'trang_thai' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'id_nguoi_dung.required' => 'Người dùng không được để trống',
            'id_nguoi_dung.exists' => 'Người dùng không tồn tại',
            'id_loai_su_co.required' => 'Loại sự cố không được để trống',
            'id_loai_su_co.exists' => 'Loại sự cố không tồn tại',
            'vi_tri_lat.required' => 'Vị trí latitude không được để trống',
            'vi_tri_lat.numeric' => 'Vị trí latitude phải là số',
            'vi_tri_lng.required' => 'Vị trí longitude không được để trống',
            'vi_tri_lng.numeric' => 'Vị trí longitude phải là số',
            'vi_tri_dia_chi.required' => 'Địa chỉ không được để trống',
            'vi_tri_dia_chi.max' => 'Địa chỉ không được quá 500 ký tự',
            'so_nguoi_bi_anh_huong.integer' => 'Số người phải là số nguyên',
            'so_nguoi_bi_anh_huong.min' => 'Số người phải lớn hơn hoặc bằng 1',
            'muc_do_khan_cap.required' => 'Mức độ khẩn cấp không được để trống',
            'muc_do_khan_cap.integer' => 'Mức độ khẩn cấp phải là số nguyên',
            'muc_do_khan_cap.min' => 'Mức độ khẩn cấp phải từ 1 đến 5',
            'muc_do_khan_cap.max' => 'Mức độ khẩn cấp phải từ 1 đến 5',
            'trang_thai.required' => 'Trạng thái không được để trống',
            'trang_thai.integer' => 'Trạng thái phải là số nguyên',
        ];
    }
}
