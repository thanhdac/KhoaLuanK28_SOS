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
        Schema::create('danh_gia_cuu_ho', function (Blueprint $table) {
            $table->id('id_danh_gia');
            $table->unsignedBigInteger('id_yeu_cau');
            $table->unsignedBigInteger('id_nguoi_dung');
            $table->tinyInteger('diem_danh_gia')->nullable();
            $table->text('noi_dung_danh_gia')->nullable();
            $table->timestamps();

            $table->foreign('id_yeu_cau')->references('id_yeu_cau')->on('yeu_cau_cuu_ho')->onDelete('cascade');
            $table->foreign('id_nguoi_dung')->references('id_nguoi_dung')->on('nguoi_dung')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danh_gia_cuu_ho');
    }
};
