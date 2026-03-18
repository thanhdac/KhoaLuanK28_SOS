<?php

namespace Database\Seeders;

use App\Models\KetQuaCuuHo;
use App\Models\PhanCongCuuHo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KetQuaCuuHoSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $assignments = PhanCongCuuHo::where('trang_thai_nhiem_vu', 'HOAN_THANH')->get();
        $count = 0;

        foreach ($assignments->take(5) as $assignment) {
            KetQuaCuuHo::updateOrCreate(
                ['id_phan_cong' => $assignment->id_phan_cong],
                [
                    'id_phan_cong' => $assignment->id_phan_cong,
                    'bao_cao_hien_truong' => "Báo cáo hiện trường: Đã kiểm soát tình hình. Kết quả: Thành công",
                    'trang_thai_ket_qua' => 'HOAN_THANH',
                    'hinh_anh_minh_chung' => 'result_image.jpg',
                    'thoi_gian_ket_thuc' => now()->subHours(rand(1, 12))
                ]
            );
            $count++;
        }

        echo "✅ Kết Quả Cứu Hộ Seeding: {$count} kết quả\n";
    }
}
