<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DanhGiaCuuHoRequest extends FormRequest
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
            'id_nguoi_dung' => 'required|exists:nguoi_dung,id_nguoi_dung',
            'diem_danh_gia' => 'required|integer|min:1|max:5',
            'noi_dung_danh_gia' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'id_yeu_cau.required' => 'Yêu cầu cứu hộ không được để trống',
            'id_yeu_cau.exists' => 'Yêu cầu cứu hộ không tồn tại',
            'id_nguoi_dung.required' => 'Người dùng không được để trống',
            'id_nguoi_dung.exists' => 'Người dùng không tồn tại',
            'diem_danh_gia.required' => 'Điểm đánh giá không được để trống',
            'diem_danh_gia.integer' => 'Điểm đánh giá phải là số nguyên',
            'diem_danh_gia.min' => 'Điểm đánh giá phải từ 1 đến 5',
            'diem_danh_gia.max' => 'Điểm đánh giá phải từ 1 đến 5',
        ];
    }
}
