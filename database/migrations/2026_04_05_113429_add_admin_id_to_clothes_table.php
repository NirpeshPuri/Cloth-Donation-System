<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('clothes', function (Blueprint $table) {
            $table->foreignId('admin_id')->nullable()->after('id')->constrained('admins')->onDelete('cascade');
            // If you have users table as admin, use:
            // $table->foreignId('admin_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('clothes', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
        });
    }
};
