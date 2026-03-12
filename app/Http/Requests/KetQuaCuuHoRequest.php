<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KetQuaCuuHoRequest extends FormRequest
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
            'id_phan_cong' => 'required|exists:phan_cong_cuu_ho,id_phan_cong',
            'bao_cao_hien_truong' => 'nullable|string',
            'trang_thai_ket_qua' => 'required|integer',
            'hinh_anh_minh_chung' => 'nullable|string',
            'thoi_gian_ket_thuc' => 'required|date_format:Y-m-d H:i:s',
        ];
    }

    public function messages()
    {
        return [
            'id_phan_cong.required' => 'Phân công cứu hộ không được để trống',
            'id_phan_cong.exists' => 'Phân công cứu hộ không tồn tại',
            'trang_thai_ket_qua.required' => 'Trạng thái kết quả không được để trống',
            'trang_thai_ket_qua.integer' => 'Trạng thái kết quả phải là số nguyên',
            'thoi_gian_ket_thuc.required' => 'Thời gian kết thúc không được để trống',
            'thoi_gian_ket_thuc.date_format' => 'Thời gian kết thúc phải có định dạng Y-m-d H:i:s',
        ];
    }
}
