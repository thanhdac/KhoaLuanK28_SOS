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
        $adminId = $this->route('admin');

        $rules = [
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email',
            'mat_khau' => 'required|string|min:6',
            'so_dien_thoai' => 'required|string|min:10',
            'id_chuc_vu' => 'required|integer',
            'trang_thai' => 'nullable|integer',
        ];

        // Nếu là update, thêm điều kiện unique bỏ qua ID hiện tại
        if ($adminId) {
            $rules['email'] = 'required|email|unique:admin,email,' . $adminId . ',id_admin';
            $rules['so_dien_thoai'] = 'required|string|min:10|unique:admin,so_dien_thoai,' . $adminId . ',id_admin';
        } else {
            // Nếu là create, check unique bình thường
            $rules['email'] = 'required|email|unique:admin,email';
            $rules['so_dien_thoai'] = 'required|string|min:10|unique:admin,so_dien_thoai';
        }

        return $rules;
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
            'mat_khau.min' => 'Mật khẩu phải tối thiểu 6 ký tự',
            'so_dien_thoai.required' => 'Số điện thoại không được để trống',
            'so_dien_thoai.min' => 'Số điện thoại phải tối thiểu 10 ký tự',
            'so_dien_thoai.unique' => 'Số điện thoại đã tồn tại',
            'id_chuc_vu.required' => 'Chức vụ không được để trống',
            'id_chuc_vu.integer' => 'Chức vụ phải là số',
            'trang_thai.integer' => 'Trạng thái phải là số nguyên',
        ];
    }
}
