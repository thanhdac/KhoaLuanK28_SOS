<?php

namespace Database\Seeders;

use App\Models\DanhGiaCuuHo;
use App\Models\YeuCauCuuHo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DanhGiaCuuHoSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $requests = YeuCauCuuHo::where('trang_thai', 'HOAN_THANH')->get();
        $count = 0;

        foreach ($requests->take(5) as $request) {
            DanhGiaCuuHo::create([
                'id_yeu_cau' => $request->id_yeu_cau,
                'id_nguoi_dung' => $request->id_nguoi_dung,
                'diem_danh_gia' => rand(4, 5),
                'noi_dung_danh_gia' => 'Xử lý tốt, chuyên nghiệp'
            ]);
            $count++;
        }

        echo "✅ Đánh Giá Cứu Hộ Seeding: {$count} đánh giá\n";
    }
}
