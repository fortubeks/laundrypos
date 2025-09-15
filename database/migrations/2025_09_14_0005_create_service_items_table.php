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
        Schema::create('service_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laundry_id')->constrained()->onDelete('cascade'); // Each laundry defines their services
            $table->foreignId('service_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('laundry_item_id')->nullable()->constrained()->onDelete('set null'); // null for per kg
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->enum('unit_type', ['per_item', 'per_kg'])->default('per_item');
            $table->integer('turnaround_time')->nullable(); // hours (optional)
            $table->timestamps();

            $table->unique(['laundry_id', 'service_category_id', 'laundry_item_id'], 'unique_service_item');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_items');
    }
};
