<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChucVu extends Model
{
    protected $table = 'chuc_vu';
    protected $primaryKey = 'id_chuc_vu';
    protected $fillable = ['ten_chuc_vu', 'slug_chuc_vu', 'mo_ta', 'tinh_trang'];

    public function admins()
    {
        return $this->hasMany(Admin::class, 'id_chuc_vu', 'id_chuc_vu');
    }

    public function phanQuyens()
    {
        return $this->hasMany(PhanQuyen::class, 'id_chuc_vu', 'id_chuc_vu');
    }
}
