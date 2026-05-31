<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_id')->constrained('application_sources')->cascadeOnDelete();
            $table->foreignId('connector_id')->nullable()->constrained('source_connectors')->nullOnDelete();
            $table->enum('status', ['pending', 'running', 'completed', 'failed', 'partial'])->default('pending');
            $table->unsignedInteger('total_items')->default(0);
            $table->unsignedInteger('created_items')->default(0);
            $table->unsignedInteger('updated_items')->default(0);
            $table->unsignedInteger('duplicate_items')->default(0);
            $table->unsignedInteger('failed_items')->default(0);
            $table->json('meta')->nullable();
            $table->longText('error_message')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_runs');
    }
};