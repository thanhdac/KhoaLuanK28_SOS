<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HangDoiXuLyRequest extends FormRequest
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
            'diem_uu_tien' => 'nullable|integer',
            'muc_khan_cap' => 'required|integer|min:1|max:5',
            'trang_thai' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'id_yeu_cau.required' => 'Yêu cầu cứu hộ không được để trống',
            'id_yeu_cau.exists' => 'Yêu cầu cứu hộ không tồn tại',
            'diem_uu_tien.integer' => 'Điểm ưu tiên phải là số nguyên',
            'muc_khan_cap.required' => 'Mức khẩn cấp không được để trống',
            'muc_khan_cap.integer' => 'Mức khẩn cấp phải là số nguyên',
            'muc_khan_cap.min' => 'Mức khẩn cấp phải từ 1 đến 5',
            'muc_khan_cap.max' => 'Mức khẩn cấp phải từ 1 đến 5',
            'trang_thai.required' => 'Trạng thái không được để trống',
            'trang_thai.integer' => 'Trạng thái phải là số nguyên',
        ];
    }
}
