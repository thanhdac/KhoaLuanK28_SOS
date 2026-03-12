<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThanhVienDoiRequest extends FormRequest
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
            'ho_ten' => 'required|string|max:255',
            'so_dien_thoai' => 'required|digits:10|unique:thanh_vien_doi,so_dien_thoai',
            'email' => 'required|email|unique:thanh_vien_doi,email',
            'mat_khau' => 'required|string|min:8',
            'vai_tro_trong_doi' => 'required|string|max:100',
            'trang_thai' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'id_doi_cuu_ho.required' => 'Đội cứu hộ không được để trống',
            'id_doi_cuu_ho.exists' => 'Đội cứu hộ không tồn tại',
            'ho_ten.required' => 'Họ và tên không được để trống',
            'ho_ten.max' => 'Họ và tên không được quá 255 ký tự',
            'so_dien_thoai.required' => 'Số điện thoại không được để trống',
            'so_dien_thoai.digits' => 'Số điện thoại phải có 10 chữ số',
            'so_dien_thoai.unique' => 'Số điện thoại đã tồn tại',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'mat_khau.required' => 'Mật khẩu không được để trống',
            'mat_khau.min' => 'Mật khẩu phải tối thiểu 8 ký tự',
            'vai_tro_trong_doi.required' => 'Vai trò trong đội không được để trống',
            'vai_tro_trong_doi.max' => 'Vai trò trong đội không được quá 100 ký tự',
            'trang_thai.required' => 'Trạng thái không được để trống',
            'trang_thai.integer' => 'Trạng thái phải là số nguyên',
        ];
    }
}
