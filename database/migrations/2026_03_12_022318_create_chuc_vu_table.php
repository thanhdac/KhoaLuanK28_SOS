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
        Schema::create('chuc_vu', function (Blueprint $table) {
            $table->id('id_chuc_vu');
            $table->string('ten_chuc_vu', 255);
            $table->string('slug_chuc_vu', 255)->nullable();
            $table->text('mo_ta')->nullable();
            $table->tinyInteger('tinh_trang')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chuc_vu');
    }
};
