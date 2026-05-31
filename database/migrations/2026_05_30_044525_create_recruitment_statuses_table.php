<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitment_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('color', 30)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_terminal')->default(false);
            $table->boolean('is_success')->default(false);
            $table->boolean('requires_note')->default(false);
            $table->boolean('can_schedule_interview')->default(false);
            $table->unsignedBigInteger('sms_template_id')->nullable();
            $table->boolean('notify_candidate')->default(false);
            $table->boolean('notify_department_manager')->default(false);
            $table->boolean('notify_interviewer')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitment_statuses');
    }
};
