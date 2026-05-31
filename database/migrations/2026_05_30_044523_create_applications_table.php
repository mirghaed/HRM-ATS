<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_position_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('source_id')->nullable()->constrained('application_sources')->nullOnDelete();
            $table->string('source_reference')->nullable();
            $table->string('source_profile_url', 500)->nullable();
            $table->unsignedBigInteger('current_status_id')->nullable();
            $table->unsignedBigInteger('expected_salary_min')->nullable();
            $table->unsignedBigInteger('expected_salary_max')->nullable();
            $table->unsignedTinyInteger('salary_fit_score')->nullable();
            $table->unsignedTinyInteger('skills_fit_score')->nullable();
            $table->unsignedTinyInteger('experience_fit_score')->nullable();
            $table->unsignedTinyInteger('overall_score')->nullable();
            $table->longText('cover_letter')->nullable();
            $table->json('form_answers')->nullable();
            $table->json('raw_payload')->nullable();
            $table->foreignId('assigned_recruiter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_department_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_interviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('rejection_reason_id')->nullable();
            $table->boolean('is_duplicate')->default(false);
            $table->foreignId('duplicate_of_application_id')->nullable()->constrained('applications')->nullOnDelete();
            $table->timestamp('applied_at')->nullable();
            $table->timestamp('last_status_changed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['job_position_id', 'current_status_id']);
            $table->index(['department_id', 'current_status_id']);
            $table->index(['assigned_recruiter_id', 'current_status_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
