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
            ['ten' => 'Lũ lụt', 'slug' => 'lu-lut', 'mo_ta' => 'Sự cố liên quan đến ngập lụt, nước dâng cao'],
            ['ten' => 'Bão', 'slug' => 'bao', 'mo_ta' => 'Sự cố do bão gây ra như gió mạnh, sập công trình'],
            ['ten' => 'Sạt lở đất', 'slug' => 'sat-lo-dat', 'mo_ta' => 'Sự cố liên quan đến sạt lở đất, núi'],
            ['ten' => 'Động đất', 'slug' => 'dong-dat', 'mo_ta' => 'Sự cố do rung chấn địa chất'],
            ['ten' => 'Sóng thần', 'slug' => 'song-than', 'mo_ta' => 'Sự cố do sóng biển dâng cao bất thường'],
            ['ten' => 'Hạn hán', 'slug' => 'han-han', 'mo_ta' => 'Thiếu nước kéo dài, ảnh hưởng sinh hoạt'],
            
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
