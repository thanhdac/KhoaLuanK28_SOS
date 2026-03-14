<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViTriDoiCuuHo extends Model
{
    protected $table = 'vi_tri_doi_cuu_ho';
    protected $primaryKey = 'id';
    protected $fillable = ['id_doi_cuu_ho', 'vi_tri_lat', 'vi_tri_lng'];

    public function doiCuuHo()
    {
        return $this->belongsTo(DoiCuuHo::class, 'id_doi_cuu_ho', 'id_doi_cuu_ho');
    }
}
