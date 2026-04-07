<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clothes', function (Blueprint $table) {
            // Make foreign keys nullable
            $table->foreignId('brand_id')->nullable()->change();
            $table->foreignId('cloth_type_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('clothes', function (Blueprint $table) {
            $table->foreignId('brand_id')->nullable(false)->change();
            $table->foreignId('cloth_type_id')->nullable(false)->change();
        });
    }
};
