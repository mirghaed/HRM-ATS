<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('career_landing_sections')->where('key', 'trust')->delete();
    }

    public function down(): void
    {
        DB::table('career_landing_sections')->updateOrInsert(
            ['key' => 'trust'],
            [
                'title' => 'اعتماد در جذب',
                'content' => null,
                'payload' => json_encode([
                    'items' => [
                        ['label' => 'فرصت شغلی فعال', 'value' => '0'],
                        ['label' => 'تیم فعال', 'value' => '0'],
                        ['label' => 'رزومه دریافتی', 'value' => '0'],
                        ['label' => 'استخدام موفق', 'value' => '0'],
                    ],
                ], JSON_UNESCAPED_UNICODE),
                'sort_order' => 8,
                'is_active' => true,
                'updated_at' => now(),
                'created_at' => now(),
            ],
        );
    }
};
