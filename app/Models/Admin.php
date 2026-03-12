<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    protected $fillable = ['ho_ten', 'email', 'mat_khau', 'so_dien_thoai', 'id_chuc_vu', 'trang_thai'];
    protected $hidden = ['mat_khau'];

    public function chucVu()
    {
        return $this->belongsTo(ChucVu::class, 'id_chuc_vu', 'id_chuc_vu');
    }

    public function trongSoPhanLoais()
    {
        return $this->hasMany(TrongSoPhanLoai::class, 'cap_nhat_boi', 'id_admin');
    }
}
