<?php

namespace Database\Seeders;

use App\Models\HangDoiXuLy;
use App\Models\YeuCauCuuHo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HangDoiXuLySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $requests = YeuCauCuuHo::all();

        foreach ($requests as $request) {
            $status = $request->trang_thai;
            $mappedStatus = $status === 'CHO_XU_LY' ? 'WAITING' : ($status === 'DANG_XU_LY' ? 'PROCESSING' : 'DONE');

            HangDoiXuLy::updateOrCreate(
                ['id_yeu_cau' => $request->id_yeu_cau],
                [
                    'id_yeu_cau' => $request->id_yeu_cau,
                    'diem_uu_tien' => $request->diem_uu_tien,
                    'muc_khan_cap' => $request->muc_do_khan_cap,
                    'trang_thai' => $mappedStatus
                ]
            );
        }

        echo "✅ Hàng Đợi Xử Lý Seeding: " . count($requests) . " hàng đợi\n";
    }
}
