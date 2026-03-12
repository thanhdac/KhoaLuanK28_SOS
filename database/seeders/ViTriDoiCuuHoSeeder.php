<?php

namespace Database\Seeders;

use App\Models\ViTriDoiCuuHo;
use App\Models\DoiCuuHo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ViTriDoiCuuHoSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $dois = DoiCuuHo::all();
        $count = 0;

        foreach ($dois as $doi) {
            // 1-2 locations per team
            for ($i = 0; $i < rand(1, 2); $i++) {
                ViTriDoiCuuHo::create([
                    'id_doi_cuu_ho' => $doi->id_doi_cuu_ho,
                    'vi_tri_lat' => $doi->vi_tri_lat + (rand(-50, 50) / 1000),
                    'vi_tri_lng' => $doi->vi_tri_lng + (rand(-50, 50) / 1000)
                ]);
                $count++;
            }
        }

        echo "✅ Vị Trí Đội Cứu Hộ Seeding: {$count} vị trí\n";
    }
}
