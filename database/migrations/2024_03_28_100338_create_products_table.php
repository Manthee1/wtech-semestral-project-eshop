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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price');
            $table->text('description');
            $table->integer('stock');
            $table->unsignedBigInteger('make_id');
            $table->enum('drivetrain', ['FrontWheelDrive', 'RearWheelDrive', 'AllWheelDrive']);
            $table->enum('body_type', ['Sedan', 'SUV', 'Truck', 'Coupe', 'Hatchback']);
            $table->integer('gas_mileage');
            $table->enum('engine_type', ['Inline4', 'V6', 'V8', 'Electric', 'Hybrid']);
            $table->integer('height');
            $table->integer('width');
            $table->integer('length');
            $table->unsignedBigInteger('model_id');
            $table->integer('horse_power');
            $table->string('passenger_capacity', 20);
            $table->integer('year');
            $table->timestamps();

            $table->foreign('make_id')->references('id')->on('product_makes');
            $table->foreign('model_id')->references('id')->on('product_models');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
