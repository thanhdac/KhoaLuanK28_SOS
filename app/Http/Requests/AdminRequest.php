<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:admin,email',
            'mat_khau' => 'required|string|min:8',
            'so_dien_thoai' => 'required|digits:10|unique:admin,so_dien_thoai',
            'id_chuc_vu' => 'required|exists:chuc_vu,id_chuc_vu',
            'trang_thai' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'ho_ten.required' => 'Họ và tên không được để trống',
            'ho_ten.max' => 'Họ và tên không được quá 255 ký tự',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'mat_khau.required' => 'Mật khẩu không được để trống',
            'mat_khau.min' => 'Mật khẩu phải tối thiểu 8 ký tự',
            'so_dien_thoai.required' => 'Số điện thoại không được để trống',
            'so_dien_thoai.digits' => 'Số điện thoại phải có 10 chữ số',
            'so_dien_thoai.unique' => 'Số điện thoại đã tồn tại',
            'id_chuc_vu.required' => 'Chức vụ không được để trống',
            'id_chuc_vu.exists' => 'Chức vụ không tồn tại',
            'trang_thai.required' => 'Trạng thái không được để trống',
            'trang_thai.integer' => 'Trạng thái phải là số nguyên',
        ];
    }
}
