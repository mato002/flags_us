<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_pages', function (Blueprint $table): void {
            if (! Schema::hasColumn('cms_pages', 'robots')) {
                $table->string('robots', 120)->nullable()->after('og_image');
            }
            if (! Schema::hasColumn('cms_pages', 'canonical_url')) {
                $table->string('canonical_url', 2048)->nullable()->after('robots');
            }
        });

        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE cms_pages MODIFY meta_description TEXT NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE cms_pages ALTER COLUMN meta_description TYPE TEXT');
        }
        // sqlite stores strings unconstrained; no-op.
    }

    public function down(): void
    {
        Schema::table('cms_pages', function (Blueprint $table): void {
            if (Schema::hasColumn('cms_pages', 'canonical_url')) {
                $table->dropColumn('canonical_url');
            }
            if (Schema::hasColumn('cms_pages', 'robots')) {
                $table->dropColumn('robots');
            }
        });
    }
};
