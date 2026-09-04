<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('global_config', function (Blueprint $table) {
        $table->string('headline_title')->nullable()->after('motto');
        $table->text('headline_subtitle')->nullable()->after('headline_title');
        $table->text('headline_img_cover')->nullable()->after('headline_subtitle');

        $table->string('headline_primary_cta_text')->nullable()->after('headline_img_cover');
        $table->text('headline_primary_cta_url')->nullable()->after('headline_primary_cta_text');

        $table->string('headline_secondary_cta_text')->nullable()->after('headline_primary_cta_url');
        $table->text('headline_secondary_cta_url')->nullable()->after('headline_secondary_cta_text');
    });
}

public function down(): void
{
    Schema::table('global_config', function (Blueprint $table) {
        $table->dropColumn([
            'headline_title', 'headline_subtitle', 'headline_img_cover',
            'headline_primary_cta_text', 'headline_primary_cta_url',
            'headline_secondary_cta_text', 'headline_secondary_cta_url',
        ]);
    });
}
};