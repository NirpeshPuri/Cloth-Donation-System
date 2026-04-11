<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('preference_type'); // search_term, gender, size, quality, category
            $table->string('preference_value');
            $table->integer('count')->default(1);
            $table->timestamp('last_used_at');
            $table->timestamps();

            $table->unique(['user_id', 'preference_type', 'preference_value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
