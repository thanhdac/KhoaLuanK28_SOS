<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class NguoiDung extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'nguoi_dung';
    protected $primaryKey = 'id_nguoi_dung';
    protected $fillable = ['ho_ten', 'so_dien_thoai', 'email', 'mat_khau', 'api_token', 'trang_thai'];
    protected $hidden = ['mat_khau', 'api_token'];

    public function yeuCauCuuHos()
    {
        return $this->hasMany(YeuCauCuuHo::class, 'id_nguoi_dung', 'id_nguoi_dung');
    }

    public function danhGias()
    {
        return $this->hasMany(DanhGiaCuuHo::class, 'id_nguoi_dung', 'id_nguoi_dung');
    }
}
