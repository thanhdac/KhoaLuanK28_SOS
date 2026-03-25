<?php

namespace Database\Seeders;

use App\Models\NguoiDung;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NguoiDungSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = [
            ['ho_ten' => 'Nguyễn Thị Hương', 'sdt' => '0901111111', 'email' => 'huong1@example.com'],
            ['ho_ten' => 'Trần Văn Hùng', 'sdt' => '0902222222', 'email' => 'hung1@example.com'],
            ['ho_ten' => 'Lê Thị Hoa', 'sdt' => '0903333333', 'email' => 'hoa1@example.com'],
            ['ho_ten' => 'Phạm Văn Hải', 'sdt' => '0904444444', 'email' => 'hai1@example.com'],
            ['ho_ten' => 'Hoàng Thị Hạnh', 'sdt' => '0905555555', 'email' => 'hanh1@example.com'],
            ['ho_ten' => 'Võ Văn Hệ', 'sdt' => '0906666666', 'email' => 'he1@example.com'],
            ['ho_ten' => 'Bùi Thị Hiền', 'sdt' => '0907777777', 'email' => 'hien1@example.com'],
            ['ho_ten' => 'Dương Văn Hậu', 'sdt' => '0908888888', 'email' => 'hau1@example.com'],
            ['ho_ten' => 'Tô Thị Hiểu', 'sdt' => '0909999999', 'email' => 'hieu1@example.com'],
            ['ho_ten' => 'Đặng Văn Hịu', 'sdt' => '0910000000', 'email' => 'hiu1@example.com']
        ];

        foreach ($users as $user) {
            NguoiDung::updateOrCreate(
                ['email' => $user['email']],
                [
                    'ho_ten' => $user['ho_ten'],
                    'so_dien_thoai' => $user['sdt'],
                    'email' => $user['email'],
                    'mat_khau' => Hash::make('user123'),
                    'trang_thai' => 1
                ]
            );
        }

        echo "✅ Người Dùng Seeding: 10 người dùng\n";
    }
}
