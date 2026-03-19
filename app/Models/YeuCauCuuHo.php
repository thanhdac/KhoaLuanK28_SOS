<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YeuCauCuuHo extends Model
{
    protected $table = 'yeu_cau_cuu_ho';
    protected $primaryKey = 'id_yeu_cau';
    protected $fillable = ['id_nguoi_dung', 'id_loai_su_co', 'vi_tri_lat', 'vi_tri_lng', 'vi_tri_dia_chi', 'chi_tiet', 'mo_ta', 'hinh_anh', 'so_nguoi_bi_anh_huong', 'muc_do_khan_cap', 'diem_uu_tien', 'trang_thai', 'thoi_gian_gui'];

    /**
     * Relationship with NguoiDung (User)
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung', 'id_nguoi_dung');
    }

    /**
     * Relationship with LoaiSuCo (Incident Type)
     */
    public function loaiSuCo()
    {
        return $this->belongsTo(LoaiSuCo::class, 'id_loai_su_co', 'id_loai_su_co');
    }

    /**
     * Relationship with HangDoiXuLy (Processing Queue)
     */
    public function hangDoiXuLy()
    {
        return $this->hasOne(HangDoiXuLy::class, 'id_yeu_cau', 'id_yeu_cau');
    }

    /**
     * Relationship with PhanLoaiAis (AI Classification)
     */
    public function phanLoaiAis()
    {
        return $this->hasMany(PhanLoaiAis::class, 'id_yeu_cau', 'id_yeu_cau');
    }

    /**
     * Relationship with PhanCongCuuHo (Assignment)
     */
    public function phanCongs()
    {
        return $this->hasMany(PhanCongCuuHo::class, 'id_yeu_cau', 'id_yeu_cau');
    }

    /**
     * Relationship with KetQuaCuuHo (Results)
     */
    public function ketQuas()
    {
        return $this->hasManyThrough(KetQuaCuuHo::class, PhanCongCuuHo::class, 'id_yeu_cau', 'id_phan_cong', 'id_yeu_cau', 'id_phan_cong');
    }

    /**
     * Relationship with DanhGiaCuuHo (Ratings)
     */
    public function danhGias()
    {
        return $this->hasMany(DanhGiaCuuHo::class, 'id_yeu_cau', 'id_yeu_cau');
    }
}