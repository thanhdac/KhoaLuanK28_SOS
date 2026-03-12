<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhanQuyen extends Model
{
    protected $table = 'phan_quyen';
    protected $primaryKey = 'id_phan_quyen';
    protected $fillable = ['id_chuc_nang', 'id_chuc_vu'];

    public function chucNang()
    {
        return $this->belongsTo(ChucNang::class, 'id_chuc_nang', 'id_chuc_nang');
    }

    public function chucVu()
    {
        return $this->belongsTo(ChucVu::class, 'id_chuc_vu', 'id_chuc_vu');
    }
}
