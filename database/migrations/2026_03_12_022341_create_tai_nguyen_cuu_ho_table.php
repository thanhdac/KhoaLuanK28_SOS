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
        Schema::create('tai_nguyen_cuu_ho', function (Blueprint $table) {
            $table->id('id_tai_nguyen');
            $table->unsignedBigInteger('id_doi_cuu_ho');
            $table->string('ten_tai_nguyen', 255);
            $table->string('loai_tai_nguyen', 100)->nullable();
            $table->integer('so_luong')->default(0);
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
        Schema::dropIfExists('tai_nguyen_cuu_ho');
    }
};
