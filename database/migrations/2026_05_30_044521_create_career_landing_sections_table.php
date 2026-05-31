<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_landing_sections', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->string('title');
            $table->string('subtitle', 500)->nullable();
            $table->longText('content')->nullable();
            $table->string('image_path')->nullable();
            $table->string('button_text', 100)->nullable();
            $table->string('button_url')->nullable();
            $table->json('payload')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_landing_sections');
    }
};