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
        Schema::create('thanh_vien_doi', function (Blueprint $table) {
            $table->id('id_thanh_vien_doi');
            $table->unsignedBigInteger('id_doi_cuu_ho');
            $table->string('ho_ten', 255);
            $table->string('so_dien_thoai', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('mat_khau', 255)->nullable();
            $table->string('vai_tro_trong_doi', 100)->nullable();
            $table->tinyInteger('trang_thai')->default(1);
            $table->timestamps();

            $table->foreign('id_doi_cuu_ho')->references('id_doi_cuu_ho')->on('doi_cuu_ho')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thanh_vien_doi');
    }
};
