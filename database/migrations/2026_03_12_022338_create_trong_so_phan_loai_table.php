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
        Schema::create('trong_so_phan_loai', function (Blueprint $table) {
            $table->id('id_trong_so');
            $table->string('ten_tieu_chi', 100);
            $table->float('trong_so');
            $table->text('mo_ta')->nullable();
            $table->unsignedBigInteger('cap_nhat_boi')->nullable();
            $table->timestamps();

            $table->foreign('cap_nhat_boi')->references('id_admin')->on('admin')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trong_so_phan_loai');
    }
};
