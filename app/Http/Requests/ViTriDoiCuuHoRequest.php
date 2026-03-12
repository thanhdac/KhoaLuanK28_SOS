<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ViTriDoiCuuHoRequest extends FormRequest
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
            'vi_tri_lat' => 'required|numeric',
            'vi_tri_lng' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'id_doi_cuu_ho.required' => 'Đội cứu hộ không được để trống',
            'id_doi_cuu_ho.exists' => 'Đội cứu hộ không tồn tại',
            'vi_tri_lat.required' => 'Vị trí latitude không được để trống',
            'vi_tri_lat.numeric' => 'Vị trí latitude phải là số',
            'vi_tri_lng.required' => 'Vị trí longitude không được để trống',
            'vi_tri_lng.numeric' => 'Vị trí longitude phải là số',
        ];
    }
}
