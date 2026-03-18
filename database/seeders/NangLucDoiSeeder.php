<?php

namespace Database\Seeders;

use App\Models\NangLucDoi;
use App\Models\DoiCuuHo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NangLucDoiSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $dois = DoiCuuHo::all();

        foreach ($dois as $doi) {
            NangLucDoi::updateOrCreate(
                ['id_doi_cuu_ho' => $doi->id_doi_cuu_ho],
                [
                    'id_doi_cuu_ho' => $doi->id_doi_cuu_ho,
                    'so_viec_dang_xu_ly' => 0,
                    'so_viec_toi_da' => 3,
                    'ty_le_hoan_thanh' => 0.90 + (rand(0, 20) / 100),
                    'thoi_gian_xu_ly_tb' => 25 + rand(5, 20)
                ]
            );
        }

        echo "✅ Năng Lực Đội Seeding: 5 năng lực đội\n";
    }
}
