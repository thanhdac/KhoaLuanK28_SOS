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
        Schema::create('hang_doi_xu_ly', function (Blueprint $table) {
            $table->id('id_hang_doi');
            $table->unsignedBigInteger('id_yeu_cau')->unique();
            $table->float('diem_uu_tien');
            $table->string('muc_khan_cap', 20)->nullable();
            $table->string('trang_thai', 20)->default('WAITING');
            $table->timestamp('thoi_gian_vao')->useCurrent();
            $table->timestamp('thoi_gian_phan_cong')->nullable();
            $table->timestamps();

            $table->foreign('id_yeu_cau')->references('id_yeu_cau')->on('yeu_cau_cuu_ho')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hang_doi_xu_ly');
    }
};
