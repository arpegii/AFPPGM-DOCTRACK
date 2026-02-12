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
        Schema::table('documents', function (Blueprint $table) {
            // Only add if column doesn't exist
            if (!Schema::hasColumn('documents', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('status')->constrained('users')->onDelete('set null');
            }
            
            // Add rejection_reason if it doesn't exist
            if (!Schema::hasColumn('documents', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
            
            if (Schema::hasColumn('documents', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
        });
    }
};