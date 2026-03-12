<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietLoaiSuCo extends Model
{
    protected $table = 'chi_tiet_loai_su_co';
    protected $primaryKey = 'id_chi_tiet';
    protected $fillable = ['id_loai_su_co', 'ten_chi_tiet', 'mo_ta'];

    public function loaiSuCo()
    {
        return $this->belongsTo(LoaiSuCo::class, 'id_loai_su_co', 'id_loai_su_co');
    }

    public function phanCongs()
    {
        return $this->hasMany(PhanCongCuuHo::class, 'id_chi_tiet_su_co', 'id_chi_tiet');
    }
}
