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
        Schema::table('doi_cuu_ho', function (Blueprint $table) {
            $table->unsignedBigInteger('id_ket_qua')->nullable()->after('vi_tri_lng');
            $table->foreign('id_ket_qua')->references('id_ket_qua')->on('ket_qua_cuu_ho')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doi_cuu_ho', function (Blueprint $table) {
            $table->dropForeign(['id_ket_qua']);
            $table->dropColumn('id_ket_qua');
        });
    }
};

