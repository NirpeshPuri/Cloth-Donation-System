<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            // Make receiver_id nullable
            $table->foreignId('receiver_id')->nullable()->change();

            // Make cloth_id nullable
            $table->foreignId('cloth_id')->nullable()->change();

            // Add admin_id
            if (! Schema::hasColumn('donations', 'admin_id')) {
                $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');
            }

            // Add pickup address
            if (! Schema::hasColumn('donations', 'pickup_address')) {
                $table->text('pickup_address')->nullable()->after('date_of_donation');
            }

            // Add notes
            if (! Schema::hasColumn('donations', 'notes')) {
                $table->text('notes')->nullable()->after('pickup_address');
            }

            // Add donation_type
            if (! Schema::hasColumn('donations', 'donation_type')) {
                $table->enum('donation_type', ['single', 'multiple'])->default('single')->after('status');
            }

            // Update status options
            $table->enum('status', ['pending', 'approved', 'processing', 'completed', 'rejected', 'cancelled'])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->foreignId('receiver_id')->nullable(false)->change();
            $table->foreignId('cloth_id')->nullable(false)->change();
            $table->dropForeign(['admin_id']);
            $table->dropColumn(['admin_id', 'pickup_address', 'notes', 'donation_type']);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending')->change();
        });
    }
};
