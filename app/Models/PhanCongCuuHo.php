<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhanCongCuuHo extends Model
{
    protected $table = 'phan_cong_cuu_ho';
    protected $primaryKey = 'id_phan_cong';
    protected $fillable = ['id_yeu_cau', 'id_doi_cuu_ho', 'id_chi_tiet_su_co', 'mo_ta', 'thoi_gian_phan_cong', 'trang_thai_nhiem_vu'];

    /**
     * Relationship with YeuCauCuuHo
     */
    public function yeuCau()
    {
        return $this->belongsTo(YeuCauCuuHo::class, 'id_yeu_cau', 'id_yeu_cau');
    }

    /**
     * Relationship with DoiCuuHo
     */
    public function doiCuuHo()
    {
        return $this->belongsTo(DoiCuuHo::class, 'id_doi_cuu_ho', 'id_doi_cuu_ho');
    }

    /**
     * Relationship with KetQuaCuuHo
     */
    public function ketQua()
    {
        return $this->hasOne(KetQuaCuuHo::class, 'id_phan_cong', 'id_phan_cong');
    }
}