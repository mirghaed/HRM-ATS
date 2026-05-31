<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->foreign('current_status_id')->references('id')->on('recruitment_statuses')->nullOnDelete();
            $table->foreign('rejection_reason_id')->references('id')->on('rejection_reasons')->nullOnDelete();
        });

        Schema::table('recruitment_statuses', function (Blueprint $table) {
            $table->foreign('sms_template_id')->references('id')->on('sms_templates')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('recruitment_statuses', function (Blueprint $table) {
            $table->dropForeign(['sms_template_id']);
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['current_status_id']);
            $table->dropForeign(['rejection_reason_id']);
        });
    }
};