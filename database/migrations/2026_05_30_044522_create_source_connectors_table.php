<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('source_connectors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_id')->constrained('application_sources')->cascadeOnDelete();
            $table->string('driver', 150);
            $table->enum('mode', ['manual', 'api', 'imap', 'excel', 'webhook'])->default('manual');
            $table->enum('status', ['disabled', 'active', 'error'])->default('disabled');
            $table->string('endpoint_url', 500)->nullable();
            $table->text('encrypted_config')->nullable();
            $table->timestamp('last_sync_at')->nullable();
            $table->longText('last_error')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('source_connectors');
    }
};