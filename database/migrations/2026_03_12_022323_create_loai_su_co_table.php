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
        Schema::create('loai_su_co', function (Blueprint $table) {
            $table->id('id_loai_su_co');
            $table->string('ten_danh_muc', 255);
            $table->string('slug_danh_muc', 255)->nullable();
            $table->text('mo_ta')->nullable();
            $table->tinyInteger('trang_thai')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loai_su_co');
    }
};
