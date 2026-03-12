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
            1 => ['Cháy nhà ở', 'Cháy toà nhà', 'Cháy xe'],
            2 => ['Đuối tại bể bơi', 'Đuối tại sông', 'Đuối tại biển'],
            3 => ['Tai nạn xe máy', 'Tai nạn ô tô'],
            4 => ['Sập nhà ở', 'Sập tòa nhà'],
            5 => ['Khí CO độc', 'Khí hóa học'],
            6 => ['Ngộ độc thực phẩm', 'Ngộ độc hóa chất'],
            7 => ['Điện giật', 'Cháy điện'],
            8 => ['Vết thương sâu', 'Gãy xương']
        ];

        $loaiSuCos = LoaiSuCo::all();

        foreach ($loaiSuCos as $loai) {
            if (isset($details[$loai->id_loai_su_co])) {
                foreach ($details[$loai->id_loai_su_co] as $detail) {
                    ChiTietLoaiSuCo::create([
                        'id_loai_su_co' => $loai->id_loai_su_co,
                        'ten_chi_tiet' => $detail,
                        'mo_ta' => "Chi tiết: {$detail}"
                    ]);
                }
            }
        }

        echo "✅ Chi Tiết Loại Sự Cố Seeding: 18 chi tiết\n";
    }
}
