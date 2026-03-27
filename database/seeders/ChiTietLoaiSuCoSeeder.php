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
        $details = [
            1 => 'Cứu nạn - mắc kẹt',
            2 => 'Hỗ trợ y tế',
            3 => 'Cung cấp lương thực',
            4 => 'Cung cấp nước sạch',
            5 => 'Hỗ trợ di dời',
            6 => 'Tìm kiếm người mất tích',
            7 => 'Khắc phục nhà cửa',
            8 => 'Khắc phục điện nước',
            9 => 'Hỗ trợ sinh hoạt',
            10 => 'Hỗ trợ người yếu thế',
        ];

        $loaiSuCos = LoaiSuCo::all();

        foreach ($loaiSuCos as $loai) {
            if (isset($details[$loai->id_loai_su_co])) {

                $detail = $details[$loai->id_loai_su_co];

                ChiTietLoaiSuCo::create([
                    'id_loai_su_co' => $loai->id_loai_su_co,
                    'ten_chi_tiet' => $detail,
                    'mo_ta' => "Chi tiết: {$detail}"
                ]);
            }
        }

        echo "✅ Chi Tiết Loại Sự Cố Seeding: 18 chi tiết\n";
    }
}
