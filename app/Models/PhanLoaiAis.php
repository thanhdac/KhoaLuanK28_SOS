<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhanLoaiAis extends Model
{
    protected $table = 'phan_loai_ais';
    protected $primaryKey = 'id_phan_loai';
    protected $fillable = ['id_yeu_cau', 'so_nguoi', 'ten_loai_su_co', 'muc_tu_bao_cao', 'thoi_gian_cho', 'diem_uu_tien', 'muc_khan_cap', 'do_tin_cay', 'ly_do', 'model_version'];

    /**
     * Relationship with YeuCauCuuHo
     */
    public function yeuCau()
    {
        return $this->belongsTo(YeuCauCuuHo::class, 'id_yeu_cau', 'id_yeu_cau');
    }
}