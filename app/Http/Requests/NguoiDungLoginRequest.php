<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class NguoiDungLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'email'         => 'required|email|unique:khach_hangs,email',
            'password'      => 'required|min:6|max:255',
            're_password'   => 'required|min:6|max:255|same:password',
            'ho_va_ten'     => 'required|max:100',
            'so_dien_thoai' => 'required|digits:10|unique:khach_hangs,so_dien_thoai',
            'ngay_sinh'     => 'required|date|before_or_equal:' . now()->subYears(12)->format('Y-m-d'),
        ];
    }

    public function messages()
    {
        return [
            'email.required'    => 'Email không được để trống.',
            'email.email'       => 'Email không đúng định dạng.',
            'email.unique'      => 'Email đã tồn tại trong hệ thống.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min'      => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.max'      => 'Mật khẩu không được quá 255 ký tự.',
            're_password.required' => 'Vui lòng nhập lại mật khẩu.',
            're_password.min'      => 'Mật khẩu xác nhận phải có ít nhất 6 ký tự.',
            're_password.max'      => 'Mật khẩu xác nhận không được quá 255 ký tự.',
            're_password.same'     => 'Mật khẩu nhập lại không khớp.',
            'ho_va_ten.required' => 'Họ và tên không được để trống.',
            'ho_va_ten.max'      => 'Họ và tên không được vượt quá 100 ký tự.',
            'so_dien_thoai.required' => 'Số điện thoại không được để trống.',
            'so_dien_thoai.unique' => 'Số điện thoại này đã đăng kí.',
            'so_dien_thoai.digits'   => 'Số điện thoại phải gồm đúng 10 chữ số.',

        ];
    }
}
