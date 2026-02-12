<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all foreign keys on the documents table
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'documents' 
            AND REFERENCED_TABLE_NAME IS NOT NULL
            AND COLUMN_NAME IN ('received_by', 'rejected_by')
        ");
        
        // Drop each foreign key found
        foreach ($foreignKeys as $fk) {
            DB::statement("ALTER TABLE documents DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }
        
        // Now drop the columns if they exist
        $columns = ['received_at', 'received_by', 'rejected_at', 'rejected_by'];
        foreach ($columns as $column) {
            // Check if column exists
            $exists = DB::select("
                SELECT COLUMN_NAME 
                FROM information_schema.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'documents' 
                AND COLUMN_NAME = '{$column}'
            ");
            
            if (!empty($exists)) {
                DB::statement("ALTER TABLE documents DROP COLUMN `{$column}`");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE documents ADD COLUMN received_at TIMESTAMP NULL');
        DB::statement('ALTER TABLE documents ADD COLUMN received_by BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE documents ADD COLUMN rejected_at TIMESTAMP NULL');
        DB::statement('ALTER TABLE documents ADD COLUMN rejected_by BIGINT UNSIGNED NULL');
    }
};