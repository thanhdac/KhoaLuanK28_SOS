<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class DoiCuuHo extends Model
{
    use HasApiTokens;

    protected $table = 'doi_cuu_ho';
    protected $primaryKey = 'id_doi_cuu_ho';
    protected $fillable = ['ten_co', 'khu_vuc_quan_ly', 'so_dien_thoai_hotline', 'vi_tri_lat', 'vi_tri_lng', 'id_ket_qua', 'trang_thai', 'mo_ta', 'email', 'mat_khau'];
    protected $hidden = ['mat_khau'];

    public function thanhViens()
    {
        return $this->hasMany(ThanhVienDoi::class, 'id_doi_cuu_ho', 'id_doi_cuu_ho');
    }

    public function taiNguyens()
    {
        return $this->hasMany(TaiNguyenCuuHo::class, 'id_doi_cuu_ho', 'id_doi_cuu_ho');
    }

    public function viTris()
    {
        return $this->hasMany(ViTriDoiCuuHo::class, 'id_doi_cuu_ho', 'id_doi_cuu_ho');
    }

    public function nangLuc()
    {
        return $this->hasOne(NangLucDoi::class, 'id_doi_cuu_ho', 'id_doi_cuu_ho');
    }

    public function loaiSuCos()
    {
        return $this->belongsToMany(LoaiSuCo::class, 'doi_cuu_ho_loai_su_co', 'id_doi_cuu_ho', 'id_loai_su_co');
    }

    public function phanCongs()
    {
        return $this->hasMany(PhanCongCuuHo::class, 'id_doi_cuu_ho', 'id_doi_cuu_ho');
    }

    public function ketQua()
    {
        return $this->belongsTo(KetQuaCuuHo::class, 'id_ket_qua', 'id_ket_qua');
    }
}
