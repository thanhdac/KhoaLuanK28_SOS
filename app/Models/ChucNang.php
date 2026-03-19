<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChucNang extends Model
{
    protected $table = 'chuc_nang';
    protected $primaryKey = 'id_chuc_nang';
    protected $fillable = ['ten_chuc_nang'];

    public function phanQuyens()
    {
        return $this->hasMany(PhanQuyen::class, 'id_chuc_nang', 'id_chuc_nang');
    }
}
