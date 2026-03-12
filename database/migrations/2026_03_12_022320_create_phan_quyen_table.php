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
        Schema::create('phan_quyen', function (Blueprint $table) {
            $table->id('id_phan_quyen');
            $table->unsignedBigInteger('id_chuc_nang');
            $table->unsignedBigInteger('id_chuc_vu');
            $table->timestamps();

            $table->foreign('id_chuc_nang')->references('id_chuc_nang')->on('chuc_nang')->onDelete('cascade');
            $table->foreign('id_chuc_vu')->references('id_chuc_vu')->on('chuc_vu')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phan_quyen');
    }
};
