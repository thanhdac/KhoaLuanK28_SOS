<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ThanhVienDoi extends Model
{
    use HasApiTokens;

    protected $table = 'thanh_vien_doi';
    protected $primaryKey = 'id_thanh_vien_doi';
    protected $fillable = ['id_doi_cuu_ho', 'ho_ten', 'so_dien_thoai', 'email', 'mat_khau', 'vai_tro_trong_doi', 'trang_thai'];
    protected $hidden = ['mat_khau'];

    public function doiCuuHo()
    {
        return $this->belongsTo(DoiCuuHo::class, 'id_doi_cuu_ho', 'id_doi_cuu_ho');
    }
}
