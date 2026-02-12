<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDocumentsAddUnitRelationship extends Migration
{
    public function up(): void
    {
        // 1. Add columns if missing
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'sender_unit_id')) {
                $table->unsignedBigInteger('sender_unit_id')->nullable()->after('document_type');
            }

            if (!Schema::hasColumn('documents', 'receiving_unit_id')) {
                $table->unsignedBigInteger('receiving_unit_id')->nullable()->after('sender_unit_id');
            }
        });

        // 2. Add foreign keys with explicit names
        Schema::table('documents', function (Blueprint $table) {
            $table->foreign('sender_unit_id', 'documents_sender_unit_fk')
                  ->references('id')->on('units')->cascadeOnDelete();

            $table->foreign('receiving_unit_id', 'documents_receiving_unit_fk')
                  ->references('id')->on('units')->cascadeOnDelete();
        });

        // 3. Drop old string columns safely
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'sender_unit')) {
                $table->dropColumn('sender_unit');
            }

            if (Schema::hasColumn('documents', 'receiving_unit')) {
                $table->dropColumn('receiving_unit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign('documents_sender_unit_fk');
            $table->dropForeign('documents_receiving_unit_fk');

            // Drop the columns
            if (Schema::hasColumn('documents', 'sender_unit_id')) {
                $table->dropColumn('sender_unit_id');
            }

            if (Schema::hasColumn('documents', 'receiving_unit_id')) {
                $table->dropColumn('receiving_unit_id');
            }

            // Restore old string columns
            if (!Schema::hasColumn('documents', 'sender_unit')) {
                $table->string('sender_unit')->nullable();
            }

            if (!Schema::hasColumn('documents', 'receiving_unit')) {
                $table->string('receiving_unit')->nullable();
            }
        });
    }
}
