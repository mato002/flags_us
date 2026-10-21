<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table): void {
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->string('badge_color', 9)->nullable();
            $table->boolean('is_system')->default(false);
        });

        Schema::create('role_access_audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->string('action', 64);
            $table->json('meta')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['role_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_access_audit_logs');

        Schema::table('roles', function (Blueprint $table): void {
            $table->dropColumn(['display_name', 'description', 'badge_color', 'is_system']);
        });
    }
};
