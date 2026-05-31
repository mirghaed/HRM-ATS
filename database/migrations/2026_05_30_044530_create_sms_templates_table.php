<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('key', 100)->unique();
            $table->string('provider', 50)->default('smsir');
            $table->string('provider_template_id', 100)->nullable();
            $table->text('body_preview')->nullable();
            $table->json('variables')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_send')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_templates');
    }
};