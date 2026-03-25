<?php

namespace Database\Seeders;

use App\Models\ThanhVienDoi;
use App\Models\DoiCuuHo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThanhVienDoiSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $dois = DoiCuuHo::all();
        $count = 0;

        foreach ($dois as $doi) {
            // 2-3 members per team
            for ($i = 1; $i <= rand(2, 3); $i++) {
                ThanhVienDoi::create([
                    'id_doi_cuu_ho' => $doi->id_doi_cuu_ho,
                    'ho_ten' => "Thành viên {$i} - {$doi->khu_vuc_quan_ly}",
                    'so_dien_thoai' => sprintf('091%07d', rand(100000, 9999999)),
                    'email' => "member{$i}@{$doi->khu_vuc_quan_ly}.example.com",
                    'mat_khau' => Hash::make('member123'),
                    'vai_tro_trong_doi' => $i == 1 ? 'Team Leader' : 'Member',
                    'trang_thai' => 1
                ]);
                $count++;
            }
        }

        echo "✅ Thành Viên Đội Seeding: {$count} thành viên đội\n";
    }
}
