<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhGiaCuuHo extends Model
{
    protected $table = 'danh_gia_cuu_ho';
    protected $primaryKey = 'id_danh_gia';
    protected $fillable = ['id_yeu_cau', 'id_nguoi_dung', 'diem_danh_gia', 'noi_dung_danh_gia'];

    /**
     * Relationship with YeuCauCuuHo
     */
    public function yeuCau()
    {
        return $this->belongsTo(YeuCauCuuHo::class, 'id_yeu_cau', 'id_yeu_cau');
    }

    /**
     * Relationship with NguoiDung
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung', 'id_nguoi_dung');
    }
}
