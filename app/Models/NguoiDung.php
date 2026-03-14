<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class NguoiDung extends Authenticatable

{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'nguoi_dung';
    protected $primaryKey = 'id_nguoi_dung';
    public $timestamps = true;

    protected $fillable = [
        'ho_ten',
        'so_dien_thoai',
        'email',
        'mat_khau',
        'trang_thai',
    ];

    protected $hidden = [
        'mat_khau',
    ];

    protected $casts = [
        'trang_thai' => 'integer',
    ];

    public function yeuCauCuuHos()
    {
        return $this->hasMany(YeuCauCuuHo::class, 'id_nguoi_dung', 'id_nguoi_dung');
    }

    public function danhGiaCuuHos()
    {
        return $this->hasMany(DanhGiaCuuHo::class, 'id_nguoi_dung', 'id_nguoi_dung');
    }
}
