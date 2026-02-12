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
            // Add received_at and received_by if they don't exist
            if (!Schema::hasColumn('documents', 'received_at')) {
                $table->timestamp('received_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('documents', 'received_by')) {
                $table->unsignedBigInteger('received_by')->nullable()->after('received_at');
                $table->foreign('received_by')->references('id')->on('users')->onDelete('set null');
            }
            
            // Add rejected columns
            if (!Schema::hasColumn('documents', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('received_by');
            }
            if (!Schema::hasColumn('documents', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');
                $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'rejected_by')) {
                $table->dropForeign(['rejected_by']);
                $table->dropColumn('rejected_by');
            }
            if (Schema::hasColumn('documents', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }
            if (Schema::hasColumn('documents', 'received_by')) {
                $table->dropForeign(['received_by']);
                $table->dropColumn('received_by');
            }
            if (Schema::hasColumn('documents', 'received_at')) {
                $table->dropColumn('received_at');
            }
        });
    }
};