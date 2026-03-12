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
        Schema::create('vi_tri_doi_cuu_ho', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_doi_cuu_ho');
            $table->decimal('vi_tri_lat', 10, 8);
            $table->decimal('vi_tri_lng', 11, 8);
            $table->timestamps();

            $table->foreign('id_doi_cuu_ho')->references('id_doi_cuu_ho')->on('doi_cuu_ho')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vi_tri_doi_cuu_ho');
    }
};
