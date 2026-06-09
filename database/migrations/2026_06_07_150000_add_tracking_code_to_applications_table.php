<?php

use App\Models\Application;
use App\Services\HRM\TrackingCodeGenerator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('tracking_code', 6)->nullable()->unique()->after('id');
        });

        $generator = app(TrackingCodeGenerator::class);

        Application::query()
            ->withTrashed()
            ->whereNull('tracking_code')
            ->orderBy('id')
            ->chunkById(100, function ($applications) use ($generator) {
                foreach ($applications as $application) {
                    $application->forceFill([
                        'tracking_code' => $generator->generate(),
                    ])->save();
                }
            });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropUnique(['tracking_code']);
            $table->dropColumn('tracking_code');
        });
    }
};
