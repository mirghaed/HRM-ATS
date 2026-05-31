<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_position_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('interviewer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('scheduled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('type', ['onsite', 'online', 'phone'])->default('online');
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'no_show', 'rescheduled'])->default('scheduled');
            $table->timestamp('start_at');
            $table->timestamp('end_at')->nullable();
            $table->string('location_title')->nullable();
            $table->text('address')->nullable();
            $table->string('online_meeting_url', 500)->nullable();
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('score')->nullable();
            $table->longText('result_note')->nullable();
            $table->boolean('send_sms_to_candidate')->default(false);
            $table->timestamp('candidate_sms_sent_at')->nullable();
            $table->timestamp('reminder_sms_sent_at')->nullable();
            $table->timestamps();

            $table->index(['interviewer_id', 'start_at']);
            $table->index(['status', 'start_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};