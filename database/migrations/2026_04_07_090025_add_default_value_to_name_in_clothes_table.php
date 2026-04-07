<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clothes', function (Blueprint $table) {
            // Set default value for name field only
            $table->string('name')->default('Unnamed Item')->change();
        });
    }

    public function down(): void
    {
        Schema::table('clothes', function (Blueprint $table) {
            $table->string('name')->default(null)->change();
        });
    }
};
