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
            $table->unsignedBigInteger('forwarded_by')->nullable()->after('status');
            $table->timestamp('forwarded_at')->nullable()->after('forwarded_by');
            $table->text('forwarding_notes')->nullable()->after('forwarded_at');
            $table->unsignedBigInteger('original_document_id')->nullable()->after('forwarding_notes');
            
            $table->foreign('forwarded_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('original_document_id')->references('id')->on('documents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['forwarded_by']);
            $table->dropForeign(['original_document_id']);
            $table->dropColumn(['forwarded_by', 'forwarded_at', 'forwarding_notes', 'original_document_id']);
        });
    }
};