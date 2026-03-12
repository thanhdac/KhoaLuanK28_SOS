<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhanCongCuuHoRequest extends FormRequest
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
            'id_yeu_cau' => 'required|exists:yeu_cau_cuu_ho,id_yeu_cau',
            'id_doi_cuu_ho' => 'required|exists:doi_cuu_ho,id_doi_cuu_ho',
            'id_chi_tiet_su_co' => 'nullable|integer',
            'mo_ta' => 'nullable|string',
            'thoi_gian_phan_cong' => 'required|date_format:Y-m-d H:i:s',
            'trang_thai_nhiem_vu' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'id_yeu_cau.required' => 'Yêu cầu cứu hộ không được để trống',
            'id_yeu_cau.exists' => 'Yêu cầu cứu hộ không tồn tại',
            'id_doi_cuu_ho.required' => 'Đội cứu hộ không được để trống',
            'id_doi_cuu_ho.exists' => 'Đội cứu hộ không tồn tại',
            'id_chi_tiet_su_co.integer' => 'Chi tiết sự cố phải là số nguyên',
            'thoi_gian_phan_cong.required' => 'Thời gian phân công không được để trống',
            'thoi_gian_phan_cong.date_format' => 'Thời gian phân công phải có định dạng Y-m-d H:i:s',
            'trang_thai_nhiem_vu.required' => 'Trạng thái nhiệm vụ không được để trống',
            'trang_thai_nhiem_vu.integer' => 'Trạng thái nhiệm vụ phải là số nguyên',
        ];
    }
}
