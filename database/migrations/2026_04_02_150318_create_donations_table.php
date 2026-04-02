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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained('users');
            $table->foreignId('receiver_id')->constrained('users');
            $table->foreignId('cloth_id')->constrained('clothes');
            $table->integer('quantity');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->date('date_of_donation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
