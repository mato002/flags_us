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
        Schema::create('homepage_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('hero_badge')->default('Outdoor Media Platform');
            $table->string('hero_title')->default('Intelligent Outdoor Advertising, Built for Scale');
            $table->text('hero_description')->nullable();
            $table->string('primary_cta_text')->default('Plan a Campaign');
            $table->string('primary_cta_link')->default('/smart-campaign-planner');
            $table->string('secondary_cta_text')->default('Talk to Sales');
            $table->string('secondary_cta_link')->default('/contact-us');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_settings');
    }
};
