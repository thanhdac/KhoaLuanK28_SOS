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
        Schema::create('chi_tiet_loai_su_co', function (Blueprint $table) {
            $table->id('id_chi_tiet');
            $table->unsignedBigInteger('id_loai_su_co');
            $table->string('ten_chi_tiet', 255);
            $table->text('mo_ta')->nullable();
            $table->timestamps();

            $table->foreign('id_loai_su_co')->references('id_loai_su_co')->on('loai_su_co')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_loai_su_co');
    }
};
