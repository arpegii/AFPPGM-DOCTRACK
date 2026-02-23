<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('documents')) {
            return;
        }

        $driver = DB::getDriverName();
        $dropColumns = [];

        foreach (['received_at', 'received_by', 'rejected_at', 'rejected_by'] as $column) {
            if (Schema::hasColumn('documents', $column)) {
                $dropColumns[] = $column;
            }
        }

        if (empty($dropColumns)) {
            return;
        }

        if ($driver === 'mysql') {
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'documents'
                AND REFERENCED_TABLE_NAME IS NOT NULL
                AND COLUMN_NAME IN ('received_by', 'rejected_by')
            ");

            foreach ($foreignKeys as $fk) {
                DB::statement("ALTER TABLE documents DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
            }
        }

        Schema::table('documents', function (Blueprint $table) use ($dropColumns): void {
            $table->dropColumn($dropColumns);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('documents')) {
            return;
        }

        Schema::table('documents', function (Blueprint $table): void {
            if (!Schema::hasColumn('documents', 'received_at')) {
                $table->timestamp('received_at')->nullable();
            }
            if (!Schema::hasColumn('documents', 'received_by')) {
                $table->unsignedBigInteger('received_by')->nullable();
            }
            if (!Schema::hasColumn('documents', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable();
            }
            if (!Schema::hasColumn('documents', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable();
            }
        });
    }
};
