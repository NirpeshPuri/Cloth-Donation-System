<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clothes', function (Blueprint $table) {
            // Add name field
            if (! Schema::hasColumn('clothes', 'name')) {
                $table->string('name')->after('id');
            }

            // Add category field
            if (! Schema::hasColumn('clothes', 'category')) {
                $table->string('category')->nullable()->after('name');
            }

            // Add gender field
            if (! Schema::hasColumn('clothes', 'gender')) {
                $table->enum('gender', ['men', 'women', 'unisex', 'kids'])->default('unisex')->after('category');
            }

            // Add color field
            if (! Schema::hasColumn('clothes', 'color')) {
                $table->string('color')->nullable()->after('size');
            }

            // Add description field
            if (! Schema::hasColumn('clothes', 'description')) {
                $table->text('description')->nullable()->after('quality');
            }
        });
    }

    public function down(): void
    {
        Schema::table('clothes', function (Blueprint $table) {
            $table->dropColumn(['name', 'category', 'gender', 'color', 'description']);
        });
    }
};
