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
        Schema::create('ket_qua_cuu_ho', function (Blueprint $table) {
            $table->id('id_ket_qua');
            $table->unsignedBigInteger('id_phan_cong')->unique();
            $table->text('bao_cao_hien_truong')->nullable();
            $table->string('trang_thai_ket_qua', 30)->default('DANG_TIEP_TUC');
            $table->string('hinh_anh_minh_chung', 500)->nullable();
            $table->timestamp('thoi_gian_ket_thuc')->nullable();
            $table->timestamps();

            $table->foreign('id_phan_cong')->references('id_phan_cong')->on('phan_cong_cuu_ho')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ket_qua_cuu_ho');
    }
};
