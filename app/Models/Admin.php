<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    protected $fillable = [
        'ho_ten',
        'email',
        'mat_khau',
        'so_dien_thoai',
        'id_chuc_vu',
        'trang_thai',
        'api_token',
    ];
    protected $hidden = ['mat_khau', 'api_token'];

    public function chucVu()
    {
        return $this->belongsTo(ChucVu::class, 'id_chuc_vu', 'id_chuc_vu');
    }

    public function trongSoPhanLoais()
    {
        return $this->hasMany(TrongSoPhanLoai::class, 'cap_nhat_boi', 'id_admin');
    }
}
