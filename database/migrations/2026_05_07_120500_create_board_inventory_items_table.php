<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('board_inventory_items', function (Blueprint $table): void {
            $table->id();
            $table->string('reference_code')->nullable();
            $table->string('location');
            $table->string('size')->nullable();
            $table->string('illumination')->nullable();
            $table->text('traffic_notes')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->string('availability_status')->default('available');
            $table->string('maintenance_status')->default('ok');
            $table->string('photo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('board_inventory_items');
    }
};
