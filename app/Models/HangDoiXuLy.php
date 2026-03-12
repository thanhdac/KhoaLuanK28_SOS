<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HangDoiXuLy extends Model
{
    protected $table = 'hang_doi_xu_ly';
    protected $primaryKey = 'id_hang_doi';
    protected $fillable = ['id_yeu_cau', 'diem_uu_tien', 'muc_khan_cap', 'trang_thai'];

    /**
     * Relationship with YeuCauCuuHo
     */
    public function yeuCau()
    {
        return $this->belongsTo(YeuCauCuuHo::class, 'id_yeu_cau', 'id_yeu_cau');
    }
}