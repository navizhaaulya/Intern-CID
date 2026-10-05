<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('major_competent', function (Blueprint $table) {
            $table->boolean('status_code')->default(true);
        });

        Schema::table('major_gallery', function (Blueprint $table) {
            $table->boolean('status_code')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('major_competent', function (Blueprint $table) {
            $table->dropColumn('status_code');
        });

        Schema::table('major_gallery', function (Blueprint $table) {
            $table->dropColumn('status_code');
        });
    }
};