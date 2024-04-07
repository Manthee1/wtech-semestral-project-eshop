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
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedBigInteger('make_id');
            $table->foreign('make_id')->references('id')->on('product_makes');
            $table->enum('drivetrain', ['FrontWheelDrive', 'RearWheelDrive', 'AllWheelDrive']);
            $table->enum('body_type', ['Sedan', 'SUV', 'Truck', 'Coupe', 'Hatchback']);
            $table->integer('gas_mileage');
            $table->enum('engine_type', ['Inline4', 'V6', 'V8', 'Electric', 'Hybrid']);
            $table->unsignedInteger('height');
            $table->unsignedInteger('width');
            $table->unsignedInteger('length');
            $table->unsignedBigInteger('model_id');
            $table->foreign('model_id')->references('id')->on('product_models');
            $table->unsignedInteger('horse_power');
            $table->string('passanger_capacity')->nullable();
            $table->integer('year');
            $table->timestamps();
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
