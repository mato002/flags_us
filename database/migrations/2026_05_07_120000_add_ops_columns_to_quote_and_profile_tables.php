<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quote_requests', function (Blueprint $table): void {
            $table->string('status')->default('new')->after('source');
            $table->text('internal_notes')->nullable()->after('status');
            $table->foreignId('assigned_to')->nullable()->after('internal_notes')->constrained('users')->nullOnDelete();
        });

        Schema::table('profile_download_requests', function (Blueprint $table): void {
            $table->timestamp('archived_at')->nullable()->after('source');
        });
    }

    public function down(): void
    {
        Schema::table('quote_requests', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('assigned_to');
            $table->dropColumn(['status', 'internal_notes']);
        });

        Schema::table('profile_download_requests', function (Blueprint $table): void {
            $table->dropColumn('archived_at');
        });
    }
};
