<?php

namespace Database\Seeders;

use App\Models\ChiTietLoaiSuCo;
use App\Models\LoaiSuCo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChiTietLoaiSuCoSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        ChiTietLoaiSuCo::query()->delete();

        $details = [
            1 => [ // Lũ lụt
                'Cứu nạn - mắc kẹt',
                'Hỗ trợ y tế',
                'Cung cấp lương thực',
                'Cung cấp nước sạch',
                'Hỗ trợ di dời',
                'Tìm kiếm người mất tích',
                'Hỗ trợ người yếu thế'
            ],

            2 => [ // Bão
                'Cứu nạn - mắc kẹt',
                'Hỗ trợ y tế',
                'Cung cấp lương thực',
                'Cung cấp nước sạch',
                'Hỗ trợ di dời',
                'Khắc phục nhà cửa',
                'Tìm kiếm người mất tích',
                'Hỗ trợ người yếu thế'
            ],

            3 => [ // Sạt lở đất
                'Cứu nạn - mắc kẹt',
                'Hỗ trợ y tế',
                'Tìm kiếm người mất tích',
                'Hỗ trợ di dời',
                'Khắc phục giao thông',
                'Hỗ trợ người yếu thế'
            ],

            4 => [ // Động đất
                'Cứu nạn - mắc kẹt',
                'Hỗ trợ y tế',
                'Tìm kiếm người mất tích',
                'Hỗ trợ di dời',
                'Khắc phục công trình',
                'Hỗ trợ người yếu thế'
            ],

            5 => [ // Sóng thần
                'Cứu nạn - mắc kẹt',
                'Hỗ trợ y tế',
                'Cung cấp lương thực',
                'Cung cấp nước sạch',
                'Hỗ trợ di dời',
                'Tìm kiếm người mất tích',
                'Hỗ trợ người yếu thế'
            ],

            6 => [ // Hạn hán
                'Cung cấp nước sạch',
                'Cung cấp lương thực',
                'Hỗ trợ y tế',
                'Hỗ trợ sinh hoạt',
                'Hỗ trợ di dời',
                'Hỗ trợ người yếu thế'
            ],
        ];

        $count = 0;

        foreach ($details as $idLoaiSuCo => $chiTiets) {
            foreach ($chiTiets as $detail) {
                ChiTietLoaiSuCo::create([
                    'id_loai_su_co' => $idLoaiSuCo,
                    'ten_chi_tiet' => $detail,
                    'mo_ta' => "Chi tiết: {$detail}"
                ]);
                $count++;
            }
        }

        echo "✅ Chi Tiết Loại Sự Cố Seeding: {$count} chi tiết\n";
    }
}
