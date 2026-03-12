<?php

namespace Database\Seeders;

use App\Models\PhanCongCuuHo;
use App\Models\YeuCauCuuHo;
use App\Models\DoiCuuHo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PhanCongCuuHoSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $requests = YeuCauCuuHo::where('trang_thai', 'DANG_XU_LY')
            ->orWhere('trang_thai', 'HOAN_THANH')
            ->get();
        $teams = DoiCuuHo::all();
        $count = 0;

        // Create assignments for processing/completed requests (max 10)
        foreach ($requests->take(10) as $request) {
            PhanCongCuuHo::create([
                'id_yeu_cau' => $request->id_yeu_cau,
                'id_doi_cuu_ho' => $teams->random()->id_doi_cuu_ho,
                'mo_ta' => "Phân công xử lý sự cố #{$request->id_yeu_cau}",
                'thoi_gian_phan_cong' => now()->subHours(rand(1, 48)),
                'trang_thai_nhiem_vu' => $request->trang_thai === 'DANG_XU_LY' ? 'DANG_XU_LY' : 'HOAN_THANH'
            ]);
            $count++;
        }

        echo "✅ Phân Công Cứu Hộ Seeding: {$count} phân công\n";
    }
}
