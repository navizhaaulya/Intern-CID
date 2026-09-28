<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('global_config', function (Blueprint $table) {
            $table->dropColumn([
                'headline_subtitle',
                'headline_img_cover',
                'headline_primary_cta_text',
                'headline_primary_cta_url',
                'headline_secondary_cta_text',
                'headline_secondary_cta_url',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('global_config', function (Blueprint $table) {
            $table->text('headline_subtitle')->nullable();
            $table->text('headline_img_cover')->nullable();
            $table->string('headline_primary_cta_text')->nullable();
            $table->text('headline_primary_cta_url')->nullable();
            $table->string('headline_secondary_cta_text')->nullable();
            $table->text('headline_secondary_cta_url')->nullable();
        });
    }
};