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
        Schema::create('quote_requests', function (Blueprint $table): void {
            $table->id();
            $table->string('full_name');
            $table->string('company_name')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('location')->nullable();
            $table->string('county')->nullable();
            $table->string('industry')->nullable();
            $table->string('campaign_objective')->nullable();
            $table->string('target_audience')->nullable();
            $table->string('media_type')->nullable();
            $table->string('campaign_duration')->nullable();
            $table->string('budget_range')->nullable();
            $table->string('campaign_slug')->nullable();
            $table->text('message')->nullable();
            $table->string('source')->default('website');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_requests');
    }
};
