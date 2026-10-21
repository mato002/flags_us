<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table): void {
            $table->string('image_path')->nullable()->after('summary');
            $table->string('icon')->nullable()->after('image_path');
            $table->boolean('is_visible_public')->default(true)->after('is_active');
        });

        Schema::table('portfolio_items', function (Blueprint $table): void {
            $table->string('media_type')->nullable()->after('campaign_location');
            $table->json('gallery_paths')->nullable()->after('image_path');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table): void {
            $table->dropColumn(['image_path', 'icon', 'is_visible_public']);
        });

        Schema::table('portfolio_items', function (Blueprint $table): void {
            $table->dropColumn(['media_type', 'gallery_paths']);
        });
    }
};
