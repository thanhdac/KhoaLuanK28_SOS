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
        Schema::create('du_lieu_heatmap', function (Blueprint $table) {
            $table->id();
            $table->decimal('vi_tri_lat', 10, 8);
            $table->decimal('vi_tri_lng', 11, 8);
            $table->integer('mat_do')->default(1);
            $table->float('diem_nguy_hiem')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('du_lieu_heatmap');
    }
};
