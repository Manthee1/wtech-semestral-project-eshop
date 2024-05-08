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
            $table->string('name', 60);
            $table->decimal('total');
            $table->string('street_address', 255);
            $table->string('city', 60);
            $table->string('country', 2);
            $table->string('tracking_number')->unique();
            $table->enum('status', ['Awaiting Payment', 'Awaiting Fulfillment', 'Awaiting Shipment', 'Awaiting Pickup', 'Partially Shipped', 'Shipped', 'Completed', 'Cancelled', 'Declined', 'Refunded', 'Disputed', 'Manual Verification Required', 'Partially Refunded'])->default('Awaiting Payment');
            $table->foreignid('user_id')->nullable()->constrained();
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
