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
            $table->string('name', 70)->nullable();
            $table->unsignedInteger('year');
            $table->unsignedInteger('price');
            $table->text('description')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedBigInteger('make_id');
            $table->foreign('make_id')->references('id')->on('product_makes');
            $table->unsignedBigInteger('model_id');
            $table->foreign('model_id')->references('id')->on('product_models');
            $table->unsignedBigInteger('drivetrain_id')->nullable();
            $table->foreign('drivetrain_id')->references('id')->on('product_drivetrains')->onDelete('set null');
            $table->unsignedBigInteger('body_type_id')->nullable();
            $table->foreign('body_type_id')->references('id')->on('product_body_types')->onDelete('set null');
            $table->unsignedBigInteger('engine_type_id')->nullable();
            $table->foreign('engine_type_id')->references('id')->on('product_engine_types')->onDelete('set null');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('length')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedInteger('horse_power')->nullable();
            $table->unsignedInteger('passenger_capacity')->nullable();
            $table->double('efficiency')->nullable();
            $table->boolean('active')->default(true);
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
