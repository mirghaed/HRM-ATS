<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key', 100)->unique();
            $table->enum('type', ['manual', 'website_form', 'job_board', 'referral', 'email', 'social', 'import', 'api', 'other'])->default('manual');
            $table->boolean('supports_auto_import')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('config')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_sources');
    }
};