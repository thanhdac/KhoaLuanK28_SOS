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
        Schema::create('nang_luc_doi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_doi_cuu_ho')->primary();
            $table->integer('so_viec_dang_xu_ly')->default(0);
            $table->integer('so_viec_toi_da')->default(3);
            $table->float('ty_le_hoan_thanh')->nullable();
            $table->integer('thoi_gian_xu_ly_tb')->nullable();
            $table->timestamps();

            $table->foreign('id_doi_cuu_ho')->references('id_doi_cuu_ho')->on('doi_cuu_ho')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nang_luc_doi');
    }
};
