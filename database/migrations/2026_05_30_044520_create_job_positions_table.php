<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->longText('responsibilities')->nullable();
            $table->longText('requirements')->nullable();
            $table->longText('benefits')->nullable();
            $table->enum('employment_type', ['full_time', 'part_time', 'project_based', 'internship', 'contract'])->default('full_time');
            $table->enum('work_mode', ['onsite', 'remote', 'hybrid'])->default('onsite');
            $table->string('location')->nullable();
            $table->unsignedBigInteger('salary_min')->nullable();
            $table->unsignedBigInteger('salary_max')->nullable();
            $table->string('salary_currency', 10)->default('IRR');
            $table->boolean('is_salary_visible_public')->default(false);
            $table->enum('status', ['draft', 'published', 'paused', 'closed', 'archived'])->default('draft');
            $table->boolean('is_public')->default(false);
            $table->foreignId('default_recruiter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('default_interviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('capacity')->nullable();
            $table->integer('priority')->default(0);
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['department_id', 'status']);
            $table->index(['status', 'is_public']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_positions');
    }
};