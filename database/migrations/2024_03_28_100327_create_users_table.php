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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('password', 255);
            $table->string('email', 255)->unique();
            $table->string('phone_number', 20)->nullable();
            $table->string('street_address', 255)->nullable();
            $table->string('city', 60)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('name_on_card', 201)->nullable();
            $table->string('card_number', 16)->nullable();
            $table->string('card_expiration_month', 2)->nullable();
            $table->string('card_expiration_year', 4)->nullable();
            $table->string('cvv', 3)->nullable();
            $table->enum('role', ['Admin', 'User'])->default('User');
            // $table->string('token')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
