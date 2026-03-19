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
            'diem_uu_tien' => 'nullable|numeric',
            'muc_khan_cap' => 'nullable|string|max:20',
            'trang_thai' => 'required|string|max:20',
        ];
    }

    public function messages()
    {
        return [
            'id_yeu_cau.required' => 'Yêu cầu cứu hộ không được để trống',
            'id_yeu_cau.exists' => 'Yêu cầu cứu hộ không tồn tại',
            'diem_uu_tien.numeric' => 'Điểm ưu tiên phải là số',
            'trang_thai.required' => 'Trạng thái không được để trống',
            'trang_thai.string' => 'Trạng thái phải là chuỗi',
        ];
    }
}
