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
        Schema::create('admin', function (Blueprint $table) {
            $table->id('id_admin');
            $table->string('ho_ten', 255);
            $table->string('email', 255)->unique();
            $table->string('mat_khau', 255);
            $table->string('so_dien_thoai', 20)->nullable();
            $table->unsignedBigInteger('id_chuc_vu')->nullable();
            $table->tinyInteger('trang_thai')->default(1);
            $table->timestamps();

            $table->foreign('id_chuc_vu')->references('id_chuc_vu')->on('chuc_vu')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
