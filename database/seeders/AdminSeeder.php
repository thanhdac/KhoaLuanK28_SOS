<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\ChucVu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $chucVus = ChucVu::all();

        $admins = [
            ['ho_ten' => 'Nguyễn Văn A', 'email' => 'admin@example.com', 'mat_khau' => bcrypt('admin123'), 'so_dien_thoai' => '0901234567', 'id_chuc_vu' => $chucVus[0]->id_chuc_vu, 'trang_thai' => 1],
            ['ho_ten' => 'Trần Thị B', 'email' => 'operator1@example.com', 'mat_khau' => bcrypt('op123'), 'so_dien_thoai' => '0912345678', 'id_chuc_vu' => $chucVus[1]->id_chuc_vu, 'trang_thai' => 1],
            ['ho_ten' => 'Lê Văn C', 'email' => 'operator2@example.com', 'mat_khau' => bcrypt('op123'), 'so_dien_thoai' => '0923456789', 'id_chuc_vu' => $chucVus[1]->id_chuc_vu, 'trang_thai' => 1],
            ['ho_ten' => 'Phạm Thị D', 'email' => 'support@example.com', 'mat_khau' => bcrypt('sup123'), 'so_dien_thoai' => '0934567890', 'id_chuc_vu' => $chucVus[1]->id_chuc_vu, 'trang_thai' => 1],
            ['ho_ten' => 'Hoàng Văn E', 'email' => 'manager@example.com', 'mat_khau' => bcrypt('mgr123'), 'so_dien_thoai' => '0945678901', 'id_chuc_vu' => $chucVus[0]->id_chuc_vu, 'trang_thai' => 1]
        ];

        foreach ($admins as $admin) {
            Admin::create($admin);
        }

        echo "✅ Admin Seeding: 5 tài khoản admin\n";
    }
}
