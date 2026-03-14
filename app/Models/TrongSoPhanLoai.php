<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrongSoPhanLoai extends Model
{
    protected $table = 'trong_so_phan_loai';
    protected $primaryKey = 'id_trong_so';
    protected $fillable = ['ten_tieu_chi', 'trong_so', 'mo_ta', 'cap_nhat_boi'];

    public function capNhatBoi()
    {
        return $this->belongsTo(Admin::class, 'cap_nhat_boi', 'id_admin');
    }
}
