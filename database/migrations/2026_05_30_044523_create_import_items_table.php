<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_run_id')->constrained()->cascadeOnDelete();
            $table->foreignId('application_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('row_number')->nullable();
            $table->string('external_reference')->nullable();
            $table->enum('status', ['pending', 'created', 'updated', 'duplicate', 'failed', 'skipped'])->default('pending');
            $table->json('raw_payload')->nullable();
            $table->json('normalized_payload')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['import_run_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_items');
    }
};