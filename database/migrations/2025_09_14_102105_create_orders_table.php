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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laundry_id')->constrained()->onDelete('cascade'); // The laundry business owner
            $table->foreignId('customer_id')->constrained()->onDelete('cascade'); // The customer placing order
            $table->enum('status', ['pending', 'processing', 'ready', 'delivered'])->default('pending');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->date('order_date');
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
