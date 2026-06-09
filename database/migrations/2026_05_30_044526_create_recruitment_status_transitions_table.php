<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitment_status_transitions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('from_status_id')
                ->constrained('recruitment_statuses')
                ->cascadeOnDelete();

            $table->foreignId('to_status_id')
                ->constrained('recruitment_statuses')
                ->cascadeOnDelete();

            $table->json('allowed_roles')->nullable();

            $table->boolean('requires_note')->default(false);
            $table->boolean('requires_interview')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(
                ['from_status_id', 'to_status_id'],
                'rst_from_to_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitment_status_transitions');
    }
};