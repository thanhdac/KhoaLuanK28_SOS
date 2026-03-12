<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NguoiDung extends Model
{
    protected $table = 'nguoi_dung';
    protected $primaryKey = 'id_nguoi_dung';
    protected $fillable = ['ho_ten', 'so_dien_thoai', 'email', 'mat_khau', 'trang_thai'];
    protected $hidden = ['mat_khau'];

    public function yeuCauCuuHos()
    {
        return $this->hasMany(YeuCauCuuHo::class, 'id_nguoi_dung', 'id_nguoi_dung');
    }

    public function danhGias()
    {
        return $this->hasMany(DanhGiaCuuHo::class, 'id_nguoi_dung', 'id_nguoi_dung');
    }
}
