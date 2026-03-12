<?php

namespace Database\Seeders;

use App\Models\DoiCuuHo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoiCuuHoSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $teams = [
            ['ten' => 'Đội Cứu Hộ Q1', 'khu_vuc' => 'Q1', 'lat' => 10.7800, 'lng' => 106.7050, 'sdt' => '0801111111'],
            ['ten' => 'Đội Cứu Hộ Q3', 'khu_vuc' => 'Q3', 'lat' => 10.8000, 'lng' => 106.7100, 'sdt' => '0802222222'],
            ['ten' => 'Đội Cứu Hộ Q5', 'khu_vuc' => 'Q5', 'lat' => 10.7700, 'lng' => 106.6800, 'sdt' => '0803333333'],
            ['ten' => 'Đội Cứu Hộ Q7', 'khu_vuc' => 'Q7', 'lat' => 10.8300, 'lng' => 106.7200, 'sdt' => '0804444444'],
            ['ten' => 'Đội Cứu Hộ Q11', 'khu_vuc' => 'Q11', 'lat' => 10.8500, 'lng' => 106.6900, 'sdt' => '0805555555']
        ];

        foreach ($teams as $team) {
            DoiCuuHo::create([
                'ten_co' => $team['ten'],
                'khu_vuc_quan_ly' => $team['khu_vuc'],
                'so_dien_thoai_hotline' => $team['sdt'],
                'vi_tri_lat' => $team['lat'],
                'vi_tri_lng' => $team['lng'],
                'trang_thai' => 'SAN_SANG',
                'mo_ta' => "Đội cứu hộ khu vực {$team['khu_vuc']}"
            ]);
        }

        echo "✅ Đội Cứu Hộ Seeding: 5 đội cứu hộ\n";
    }
}
