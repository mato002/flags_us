<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quote_requests', function (Blueprint $table): void {
            $table->index('created_at', 'quote_requests_created_at_index');
        });

        Schema::table('portfolio_items', function (Blueprint $table): void {
            $table->index(['is_published', 'sort_order'], 'portfolio_items_pub_sort_idx');
            $table->index('category', 'portfolio_items_category_idx');
        });

        Schema::table('profile_download_requests', function (Blueprint $table): void {
            $table->index('created_at', 'profile_download_requests_created_idx');
        });

        Schema::table('services', function (Blueprint $table): void {
            $table->index('is_active', 'services_active_idx');
        });
    }

    public function down(): void
    {
        Schema::table('quote_requests', function (Blueprint $table): void {
            $table->dropIndex('quote_requests_created_at_index');
        });

        Schema::table('portfolio_items', function (Blueprint $table): void {
            $table->dropIndex('portfolio_items_pub_sort_idx');
            $table->dropIndex('portfolio_items_category_idx');
        });

        Schema::table('profile_download_requests', function (Blueprint $table): void {
            $table->dropIndex('profile_download_requests_created_idx');
        });

        Schema::table('services', function (Blueprint $table): void {
            $table->dropIndex('services_active_idx');
        });
    }
};
