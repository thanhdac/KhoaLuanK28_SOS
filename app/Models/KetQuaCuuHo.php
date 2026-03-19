<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KetQuaCuuHo extends Model
{
    protected $table = 'ket_qua_cuu_ho';
    protected $primaryKey = 'id_ket_qua';
    protected $fillable = ['id_phan_cong', 'bao_cao_hien_truong', 'trang_thai_ket_qua', 'hinh_anh_minh_chung', 'thoi_gian_ket_thuc'];

    /**
     * Relationship with PhanCongCuuHo
     */
    public function phanCong()
    {
        return $this->belongsTo(PhanCongCuuHo::class, 'id_phan_cong', 'id_phan_cong');
    }

    public function doiCuuHo()
    {
        return $this->hasOne(DoiCuuHo::class, 'id_ket_qua', 'id_ket_qua');
    }
}