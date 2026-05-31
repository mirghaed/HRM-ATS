<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('application_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('interview_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sms_template_id')->nullable()->constrained()->nullOnDelete();
            $table->string('mobile', 30);
            $table->string('provider', 50)->default('smsir');
            $table->string('provider_template_id', 100)->nullable();
            $table->text('message_text')->nullable();
            $table->json('parameters')->nullable();
            $table->enum('status', ['pending', 'sent', 'failed', 'delivered', 'unknown'])->default('pending');
            $table->string('provider_message_id')->nullable();
            $table->json('provider_response')->nullable();
            $table->foreignId('sent_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();

            $table->index(['mobile', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};