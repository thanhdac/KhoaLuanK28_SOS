<?php

namespace Database\Seeders;

use App\Models\LoaiSuCo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoaiSuCoSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $types = [
            ['ten' => 'Cháy', 'slug' => 'chay', 'mo_ta' => 'Sự cố liên quan đến Cháy'],
            ['ten' => 'Đuối nước', 'slug' => 'suoi-nuoc', 'mo_ta' => 'Sự cố liên quan đến Đuối nước'],
            ['ten' => 'Tai nạn giao thông', 'slug' => 'tai-nan-giao-thong', 'mo_ta' => 'Sự cố liên quan đến Tai nạn giao thông'],
            ['ten' => 'Sập nhà', 'slug' => 'sap-nha', 'mo_ta' => 'Sự cố liên quan đến Sập nhà'],
            ['ten' => 'Ngạch độc', 'slug' => 'ngach-doc', 'mo_ta' => 'Sự cố liên quan đến Ngạch độc'],
            ['ten' => 'Ngộ độc', 'slug' => 'ngo-doc', 'mo_ta' => 'Sự cố liên quan đến Ngộ độc'],
            ['ten' => 'Hiểm họa điện', 'slug' => 'hiem-hoa-dien', 'mo_ta' => 'Sự cố liên quan đến Hiểm họa điện'],
            ['ten' => 'Thương tích', 'slug' => 'thuong-tich', 'mo_ta' => 'Sự cố liên quan đến Thương tích']
        ];

        foreach ($types as $type) {
            LoaiSuCo::updateOrCreate(
                ['slug_danh_muc' => $type['slug']],
                [
                    'ten_danh_muc' => $type['ten'],
                    'slug_danh_muc' => $type['slug'],
                    'mo_ta' => $type['mo_ta'],
                    'trang_thai' => 1
                ]
            );
        }

        echo "✅ Loại Sự Cố Seeding: 8 loại sự cố\n";
    }
}
