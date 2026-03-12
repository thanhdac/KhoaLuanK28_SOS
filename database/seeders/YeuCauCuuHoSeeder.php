<?php

namespace Database\Seeders;

use App\Models\YeuCauCuuHo;
use App\Models\NguoiDung;
use App\Models\LoaiSuCo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class YeuCauCuuHoSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = NguoiDung::pluck('id_nguoi_dung')->toArray();
        $types = LoaiSuCo::pluck('id_loai_su_co')->toArray();

        $locations = [
            ['dia_chi' => '123 Đường Lê Lợi, Q1', 'lat' => 10.7769, 'lng' => 106.7009],
            ['dia_chi' => '456 Đường Nguyễn Huệ, Q1', 'lat' => 10.7800, 'lng' => 106.7050],
            ['dia_chi' => 'Bể bơi Tao Đàn, Q3', 'lat' => 10.8000, 'lng' => 106.7100],
            ['dia_chi' => 'Sông Sài Gòn, Q7', 'lat' => 10.8300, 'lng' => 106.7200],
            ['dia_chi' => 'Đường Cách Mạng Tháng 8, Q3', 'lat' => 10.7900, 'lng' => 106.7150],
            ['dia_chi' => 'Chợ Biến, Q1', 'lat' => 10.7750, 'lng' => 106.6950],
            ['dia_chi' => 'Công viên Tao Đàn, Q3', 'lat' => 10.8050, 'lng' => 106.6980],
            ['dia_chi' => 'Bờ Bắc, Q7', 'lat' => 10.8250, 'lng' => 106.7100],
            ['dia_chi' => 'Suối Tiên, Q9', 'lat' => 10.8500, 'lng' => 106.7500],
            ['dia_chi' => 'Đại lộ Thống Nhất, Q11', 'lat' => 10.8600, 'lng' => 106.6800]
        ];

        $statuses = ['CHO_XU_LY', 'DANG_XU_LY', 'HOAN_THANH', 'HUY_BO'];
        $urgencies = ['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'];

        // Create 15 help requests
        for ($i = 0; $i < 15; $i++) {
            $location = $locations[$i % count($locations)];
            $status = $statuses[$i % count($statuses)];
            $urgency = $urgencies[rand(0, 3)];

            YeuCauCuuHo::create([
                'id_nguoi_dung' => $users[array_rand($users)],
                'id_loai_su_co' => $types[array_rand($types)],
                'vi_tri_lat' => $location['lat'],
                'vi_tri_lng' => $location['lng'],
                'vi_tri_dia_chi' => $location['dia_chi'],
                'chi_tiet' => "Chi tiết sự cố #{$i}",
                'mo_ta' => "Mô tả sự cố #{$i}: Cần cứu hộ ngay",
                'hinh_anh' => "image_{$i}.jpg",
                'so_nguoi_bi_anh_huong' => rand(1, 10),
                'muc_do_khan_cap' => $urgency,
                'diem_uu_tien' => rand(1, 10),
                'trang_thai' => $status
            ]);
        }

        echo "✅ Yêu Cầu Cứu Hộ Seeding: 15 yêu cầu\n";
    }
}
