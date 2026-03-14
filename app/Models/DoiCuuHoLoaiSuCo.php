<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoiCuuHoLoaiSuCo extends Model
{
    protected $table = 'doi_cuu_ho_loai_su_co';
    protected $fillable = ['id_doi_cuu_ho', 'id_loai_su_co'];

    public function doiCuuHo()
    {
        return $this->belongsTo(DoiCuuHo::class, 'id_doi_cuu_ho', 'id_doi_cuu_ho');
    }

    public function loaiSuCo()
    {
        return $this->belongsTo(LoaiSuCo::class, 'id_loai_su_co', 'id_loai_su_co');
    }
}
