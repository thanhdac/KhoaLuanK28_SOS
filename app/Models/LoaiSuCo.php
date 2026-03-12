<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoaiSuCo extends Model
{
    protected $table = 'loai_su_co';
    protected $primaryKey = 'id_loai_su_co';
    protected $fillable = ['ten_danh_muc', 'slug_danh_muc', 'mo_ta', 'trang_thai'];

    public function chiTiets()
    {
        return $this->hasMany(ChiTietLoaiSuCo::class, 'id_loai_su_co', 'id_loai_su_co');
    }

    public function yeuCauCuuHos()
    {
        return $this->hasMany(YeuCauCuuHo::class, 'id_loai_su_co', 'id_loai_su_co');
    }

    public function doiCuuHos()
    {
        return $this->belongsToMany(DoiCuuHo::class, 'doi_cuu_ho_loai_su_co', 'id_loai_su_co', 'id_doi_cuu_ho');
    }
}
