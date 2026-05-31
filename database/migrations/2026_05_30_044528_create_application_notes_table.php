<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('type', ['internal_note', 'phone_call_note', 'interview_note', 'salary_note', 'rejection_note', 'system_note'])->default('internal_note');
            $table->longText('body');
            $table->enum('visibility', ['hr_only', 'department', 'all_internal'])->default('hr_only');
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();

            $table->index(['application_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_notes');
    }
};