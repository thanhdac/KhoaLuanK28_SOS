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
    ['ten' => 'Đội Cứu Hộ Lũ Lụt ', 'khu_vuc' => 'Hải Châu', 'lat' => 16.0600, 'lng' => 108.2200, 'sdt' => '0901111111'],
    ['ten' => 'Đội Cứu Nạn Sạt Lở ', 'khu_vuc' => 'Liên Chiểu', 'lat' => 16.0900, 'lng' => 108.1500, 'sdt' => '0902222222'],
    ['ten' => 'Đội Sơ Cứu Y Tế ', 'khu_vuc' => 'Thanh Khê', 'lat' => 16.0700, 'lng' => 108.2000, 'sdt' => '0903333333'],
    ['ten' => 'Đội Ứng Phó Bão ', 'khu_vuc' => 'Sơn Trà', 'lat' => 16.1000, 'lng' => 108.2500, 'sdt' => '0904444444'],
    ['ten' => 'Đội Hỗ Trợ Di Dời Sơn', 'khu_vuc' => 'Ngũ Hành Sơn', 'lat' => 16.0000, 'lng' => 108.2600, 'sdt' => '0905555555']
];

        foreach ($teams as $team) {
            DoiCuuHo::updateOrCreate(
                ['ten_co' => $team['ten']],
                [
                    'ten_co' => $team['ten'],
                    'khu_vuc_quan_ly' => $team['khu_vuc'],
                    'so_dien_thoai_hotline' => $team['sdt'],
                    'vi_tri_lat' => $team['lat'],
                    'vi_tri_lng' => $team['lng'],
                    'trang_thai' => 'SAN_SANG',
                    'mo_ta' => "Đội cứu hộ khu vực {$team['khu_vuc']}"
                ]
            );
        }

        echo "✅ Đội Cứu Hộ Seeding: 5 đội cứu hộ\n";
    }
}
