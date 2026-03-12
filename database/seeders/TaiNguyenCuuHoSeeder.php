<?php

namespace Database\Seeders;

use App\Models\TaiNguyenCuuHo;
use App\Models\DoiCuuHo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaiNguyenCuuHoSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $dois = DoiCuuHo::all();
        $resources = ['Xe cứu hộ', 'Thiết bị chữa cháy', 'Thiết bị y tế', 'Thiết bị điều hành'];
        $resourceTypes = ['Vehicle', 'Equipment', 'Medical', 'Communication'];
        $count = 0;

        foreach ($dois as $doi) {
            // 3-4 resources per team
            for ($i = 0; $i < rand(3, 4); $i++) {
                TaiNguyenCuuHo::create([
                    'id_doi_cuu_ho' => $doi->id_doi_cuu_ho,
                    'ten_tai_nguyen' => $resources[$i] ?? 'Tài nguyên khác',
                    'loai_tai_nguyen' => $resourceTypes[$i] ?? 'Other',
                    'so_luong' => rand(1, 5),
                    'trang_thai' => 1
                ]);
                $count++;
            }
        }

        echo "✅ Tài Nguyên Cứu Hộ Seeding: {$count} tài nguyên\n";
    }
}
