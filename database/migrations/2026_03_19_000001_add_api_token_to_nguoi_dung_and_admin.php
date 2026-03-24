<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nguoi_dung', function (Blueprint $table) {
            $table->string('api_token', 80)->nullable()->unique()->after('mat_khau');
        });

        Schema::table('admin', function (Blueprint $table) {
            $table->string('api_token', 80)->nullable()->unique()->after('mat_khau');
        });
    }

    public function down(): void
    {
        Schema::table('nguoi_dung', function (Blueprint $table) {
            $table->dropColumn('api_token');
        });

        Schema::table('admin', function (Blueprint $table) {
            $table->dropColumn('api_token');
        });
    }
};
