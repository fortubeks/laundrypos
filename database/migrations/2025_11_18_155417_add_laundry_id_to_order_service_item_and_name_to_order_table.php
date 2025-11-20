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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('name')->after('id');
        });

        Schema::table('order_service_item', function (Blueprint $table) {
            $table->unsignedBigInteger('laundry_id')->after('id');
            $table->string('name')->after('laundry_id');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::table('order_service_item', function (Blueprint $table) {
            $table->dropColumn('laundry_id');
        });
    }
};
