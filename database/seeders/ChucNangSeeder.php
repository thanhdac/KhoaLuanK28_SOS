<?php

namespace Database\Seeders;

use App\Models\ChucNang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChucNangSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $chucNangs = [
            'Tạo yêu cầu cứu hộ',
            'Xem danh sách yêu cầu',
            'Cập nhật trạng thái yêu cầu',
            'Phân công nhiệm vụ',
            'Quản lý đội cứu hộ',
            'Quản lý thành viên đội',
            'Quản lý tài nguyên',
            'Xem thống kê báo cáo',
            'Quản lý loại sự cố',
            'Xem heatmap'
        ];

        foreach ($chucNangs as $ten) {
            ChucNang::create(['ten_chuc_nang' => $ten]);
        }

        echo "✅ Chức Năng Seeding: 10 chức năng\n";
    }
}
