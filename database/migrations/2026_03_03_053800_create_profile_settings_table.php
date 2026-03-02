<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_settings', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('label_id')->nullable();
            $blueprint->string('label_en')->nullable();
            $blueprint->string('title_id')->nullable();
            $blueprint->string('title_en')->nullable();
            $blueprint->text('description_id')->nullable();
            $blueprint->text('description_en')->nullable();
            $blueprint->string('image_main')->nullable();
            $blueprint->string('image_secondary')->nullable();
            $blueprint->string('stat_count')->nullable();
            $blueprint->string('stat_label_id')->nullable();
            $blueprint->string('stat_label_en')->nullable();
            $blueprint->string('pillar_nature_title_id')->nullable();
            $blueprint->string('pillar_nature_title_en')->nullable();
            $blueprint->text('pillar_nature_desc_id')->nullable();
            $blueprint->text('pillar_nature_desc_en')->nullable();
            $blueprint->string('pillar_heritage_title_id')->nullable();
            $blueprint->string('pillar_heritage_title_en')->nullable();
            $blueprint->text('pillar_heritage_desc_id')->nullable();
            $blueprint->text('pillar_heritage_desc_en')->nullable();
            $blueprint->string('pillar_arts_title_id')->nullable();
            $blueprint->string('pillar_arts_title_en')->nullable();
            $blueprint->text('pillar_arts_desc_id')->nullable();
            $blueprint->text('pillar_arts_desc_en')->nullable();
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_settings');
    }
};
