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

        $loaiSuCos = LoaiSuCo::all();
        $count = 0;

        foreach ($loaiSuCos as $loai) {
            if (isset($detailsMap[$loai->id_loai_su_co])) {
                foreach ($detailsMap[$loai->id_loai_su_co] as $detail) {
                    ChiTietLoaiSuCo::create([
                        'id_loai_su_co' => $loai->id_loai_su_co,
                        'ten_chi_tiet' => $detail,
                        'mo_ta' => "Chi tiết: {$detail}"
                    ]);
                    $count++;
                }
            }
        }

        echo "✅ Chi Tiết Loại Sự Cố Seeding: {$count} chi tiết\n";
    }
}
