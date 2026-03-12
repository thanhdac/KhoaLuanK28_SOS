<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoiCuuHoLoaiSuCo extends Model
{
    protected $table = 'doi_cuu_ho_loai_su_co';
    protected $fillable = ['id_doi_cuu_ho', 'id_loai_su_co'];
}