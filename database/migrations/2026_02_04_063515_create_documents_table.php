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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_number')->unique();
            $table->string('title');
            $table->string('document_type');
            $table->unsignedBigInteger('sender_unit_id');
            $table->unsignedBigInteger('receiving_unit_id');
            $table->string('file_path')->nullable();
            $table->enum('status', ['incoming', 'received', 'outgoing', 'rejected'])->default('incoming');
            $table->timestamps();

            // Foreign keys
            $table->foreign('sender_unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('receiving_unit_id')->references('id')->on('units')->onDelete('cascade');
            
            // Indexes for better query performance
            $table->index('sender_unit_id');
            $table->index('receiving_unit_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};