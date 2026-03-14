<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NangLucDoi extends Model
{
    protected $table = 'nang_luc_doi';
    protected $primaryKey = 'id_doi_cuu_ho';
    protected $fillable = ['so_viec_dang_xu_ly', 'so_viec_toi_da', 'ty_le_hoan_thanh', 'thoi_gian_xu_ly_tb'];

    public function doiCuuHo()
    {
        return $this->belongsTo(DoiCuuHo::class, 'id_doi_cuu_ho', 'id_doi_cuu_ho');
    }
}
