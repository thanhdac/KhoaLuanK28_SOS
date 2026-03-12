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
        Schema::create('doi_cuu_ho_loai_su_co', function (Blueprint $table) {
            $table->unsignedBigInteger('id_doi_cuu_ho');
            $table->unsignedBigInteger('id_loai_su_co');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->primary(['id_doi_cuu_ho', 'id_loai_su_co']);
            $table->foreign('id_doi_cuu_ho')->references('id_doi_cuu_ho')->on('doi_cuu_ho')->onDelete('cascade');
            $table->foreign('id_loai_su_co')->references('id_loai_su_co')->on('loai_su_co')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doi_cuu_ho_loai_su_co');
    }
};
