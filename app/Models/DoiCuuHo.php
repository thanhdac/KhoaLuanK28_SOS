<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoiCuuHo extends Model
{
    protected $table = 'doi_cuu_ho';
    protected $primaryKey = 'id_doi_cuu_ho';
    protected $fillable = ['ten_co', 'khu_vuc_quan_ly', 'so_dien_thoai_hotline', 'vi_tri_lat', 'vi_tri_lng', 'trang_thai', 'mo_ta'];
}