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
        Schema::create('phan_loai_ais', function (Blueprint $table) {
            $table->id('id_phan_loai');
            $table->unsignedBigInteger('id_yeu_cau');
            $table->integer('so_nguoi')->nullable();
            $table->string('ten_loai_su_co', 100)->nullable();
            $table->tinyInteger('muc_tu_bao_cao')->nullable();
            $table->integer('thoi_gian_cho')->nullable();
            $table->float('diem_uu_tien')->nullable();
            $table->string('muc_khan_cap', 20)->nullable();
            $table->float('do_tin_cay')->nullable();
            $table->text('ly_do')->nullable();
            $table->string('model_version', 20)->nullable();
            $table->timestamps();

            $table->foreign('id_yeu_cau')->references('id_yeu_cau')->on('yeu_cau_cuu_ho')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phan_loai_ais');
    }
};
