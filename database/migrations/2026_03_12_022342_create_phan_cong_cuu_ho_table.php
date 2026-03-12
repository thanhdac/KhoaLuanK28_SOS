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
        Schema::create('phan_cong_cuu_ho', function (Blueprint $table) {
            $table->id('id_phan_cong');
            $table->unsignedBigInteger('id_yeu_cau');
            $table->unsignedBigInteger('id_doi_cuu_ho');
            $table->unsignedBigInteger('id_chi_tiet_su_co')->nullable();
            $table->text('mo_ta')->nullable();
            $table->timestamp('thoi_gian_phan_cong')->nullable();
            $table->string('trang_thai_nhiem_vu', 20)->default('MOI');
            $table->timestamps();

            $table->foreign('id_yeu_cau')->references('id_yeu_cau')->on('yeu_cau_cuu_ho')->onDelete('cascade');
            $table->foreign('id_doi_cuu_ho')->references('id_doi_cuu_ho')->on('doi_cuu_ho')->onDelete('cascade');
            $table->foreign('id_chi_tiet_su_co')->references('id_chi_tiet')->on('chi_tiet_loai_su_co')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phan_cong_cuu_ho');
    }
};
