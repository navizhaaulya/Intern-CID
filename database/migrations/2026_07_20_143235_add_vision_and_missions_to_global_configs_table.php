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
    Schema::table('global_config', function (Blueprint $table) {
        $table->text('vision')->nullable()->after('video_profile');
        $table->json('missions')->nullable()->after('vision');
    });
}

public function down(): void
{
    Schema::table('global_config', function (Blueprint $table) {
        $table->dropColumn(['vision', 'missions']);
    });
}
};