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
            $table->string('username');
            $table->string('password');
            $table->string('email');
            $table->string('phone', 20);
            $table->string('street_address');
            $table->string('city');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('card_name');
            $table->string('card_number', 20);
            $table->string('card_expiration_date', 10);
            $table->string('cvv', 4);
            $table->string('role', 50);
            $table->string('token', 255);
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
