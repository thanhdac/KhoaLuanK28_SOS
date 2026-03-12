<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DuLieuHeatmap extends Model
{
    protected $table = 'du_lieu_heatmap';
    protected $primaryKey = 'id';
    protected $fillable = ['vi_tri_lat', 'vi_tri_lng', 'mat_do', 'diem_nguy_hiem'];
}