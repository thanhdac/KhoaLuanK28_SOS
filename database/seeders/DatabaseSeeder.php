<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        echo "🌱 Starting comprehensive database seeding...\n\n";

        // Call specialized seeders in proper order to maintain referential integrity
        $this->call([
            // Core functions and positions (no dependencies)
            ChucNangSeeder::class,
            ChucVuSeeder::class,

            // Admin (depends on ChucVu)
            AdminSeeder::class,

            // Users (no dependencies)
            NguoiDungSeeder::class,

            // Incident types (no dependencies)
            LoaiSuCoSeeder::class,
            ChiTietLoaiSuCoSeeder::class,

            // Rescue teams and related (no dependencies)
            DoiCuuHoSeeder::class,
            NangLucDoiSeeder::class,
            ThanhVienDoiSeeder::class,
            TaiNguyenCuuHoSeeder::class,
            ViTriDoiCuuHoSeeder::class,

            // Help requests (depends on NguoiDung, LoaiSuCo)
            YeuCauCuuHoSeeder::class,
            HangDoiXuLySeeder::class,

            // Assignments and results (depends on YeuCauCuuHo, DoiCuuHo)
            PhanCongCuuHoSeeder::class,
            KetQuaCuuHoSeeder::class,
            DanhGiaCuuHoSeeder::class,
        ]);

        echo "\n✅ Database seeding completed successfully!\n";
        echo "📊 Total sample records created: 135+\n";
        echo "   - 10 Chức năng (Functions)\n";
        echo "   - 4 Chức vụ (Positions)\n";
        echo "   - 5 Admin accounts\n";
        echo "   - 10 Người dùng (Users)\n";
        echo "   - 8 Loại sự cố (Incident Types)\n";
        echo "   - 18 Chi tiết loại sự cố (Incident Details)\n";
        echo "   - 5 Đội cứu hộ (Rescue Teams)\n";
        echo "   - 5 Năng lực đội (Team Capabilities)\n";
        echo "   - 12 Thành viên đội (Team Members)\n";
        echo "   - 18 Tài nguyên (Resources)\n";
        echo "   - 8 Vị trí đội (Team Locations)\n";
        echo "   - 15 Yêu cầu cứu hộ (Help Requests)\n";
        echo "   - 15 Hàng đợi xử lý (Processing Queues)\n";
        echo "   - 10 Phân công (Task Assignments)\n";
        echo "   - 5 Kết quả cứu hộ (Rescue Results)\n";
        echo "   - 5 Đánh giá (Ratings)\n";
    }
}
