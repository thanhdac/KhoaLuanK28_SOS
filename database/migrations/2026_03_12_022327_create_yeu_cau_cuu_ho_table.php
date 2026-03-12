<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('yeu_cau_cuu_ho', function (Blueprint $table) {
            $table->id('id_yeu_cau');
            $table->unsignedBigInteger('id_nguoi_dung');
            $table->unsignedBigInteger('id_loai_su_co');
            $table->decimal('vi_tri_lat', 10, 8);
            $table->decimal('vi_tri_lng', 11, 8);
            $table->string('vi_tri_dia_chi', 500)->nullable();
            $table->text('chi_tiet')->nullable();
            $table->text('mo_ta')->nullable();
            $table->string('hinh_anh', 500)->nullable();
            $table->integer('so_nguoi_bi_anh_huong')->default(1);
            $table->string('muc_do_khan_cap', 20)->default('MEDIUM');
            $table->float('diem_uu_tien')->default(0);
            $table->string('trang_thai', 30)->default('CHO_XU_LY');
            $table->timestamp('thoi_gian_gui')->useCurrent();
            $table->timestamps();

            $table->foreign('id_nguoi_dung')->references('id_nguoi_dung')->on('nguoi_dung')->onDelete('cascade');
            $table->foreign('id_loai_su_co')->references('id_loai_su_co')->on('loai_su_co')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yeu_cau_cuu_ho');
    }
};
