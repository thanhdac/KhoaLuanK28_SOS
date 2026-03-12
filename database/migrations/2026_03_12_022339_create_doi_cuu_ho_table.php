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
        Schema::create('doi_cuu_ho', function (Blueprint $table) {
            $table->id('id_doi_cuu_ho');
            $table->string('ten_co', 255);
            $table->string('khu_vuc_quan_ly', 255)->nullable();
            $table->string('so_dien_thoai_hotline', 20)->nullable();
            $table->decimal('vi_tri_lat', 10, 8)->nullable();
            $table->decimal('vi_tri_lng', 11, 8)->nullable();
            $table->string('trang_thai', 30)->default('SAN_SANG');
            $table->text('mo_ta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doi_cuu_ho');
    }
};
