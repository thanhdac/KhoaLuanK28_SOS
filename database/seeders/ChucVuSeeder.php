<?php

namespace Database\Seeders;

use App\Models\ChucVu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChucVuSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $chucVuData = [
            ['ten_chuc_vu' => 'Quản trị viên', 'slug_chuc_vu' => 'admin', 'mo_ta' => 'Quản trị toàn bộ hệ thống', 'tinh_trang' => 1],
            ['ten_chuc_vu' => 'Điều hành', 'slug_chuc_vu' => 'operator', 'mo_ta' => 'Điều hành phân công', 'tinh_trang' => 1],
            ['ten_chuc_vu' => 'Trưởng đội', 'slug_chuc_vu' => 'team_leader', 'mo_ta' => 'Quản lý đội cứu hộ', 'tinh_trang' => 1],
            ['ten_chuc_vu' => 'Thành viên', 'slug_chuc_vu' => 'member', 'mo_ta' => 'Thành viên đội', 'tinh_trang' => 1]
        ];

        foreach ($chucVuData as $data) {
            ChucVu::create($data);
        }

        echo "✅ Chức Vụ Seeding: 4 chức vụ\n";
    }
}
