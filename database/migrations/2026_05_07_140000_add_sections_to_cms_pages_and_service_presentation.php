<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_pages', function (Blueprint $table): void {
            $table->json('sections')->nullable()->after('meta_description');
        });

        Schema::table('services', function (Blueprint $table): void {
            $table->string('format_key', 32)->nullable()->after('slug');
            $table->json('public_sections')->nullable()->after('summary');
        });
    }

    public function down(): void
    {
        Schema::table('cms_pages', function (Blueprint $table): void {
            $table->dropColumn('sections');
        });

        Schema::table('services', function (Blueprint $table): void {
            $table->dropColumn(['format_key', 'public_sections']);
        });
    }
};
