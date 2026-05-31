<?php

namespace Database\Seeders;

use App\Models\RejectionReason;
use Illuminate\Database\Seeder;

class RejectionReasonSeeder extends Seeder
{
    public function run(): void
    {
        $reasons = [
            'عدم تطابق مهارت‌های فنی',
            'تجربه ناکافی',
            'عدم تناسب انتظارات حقوقی',
            'عدم حضور در مصاحبه',
            'عدم تطابق فرهنگی',
        ];

        foreach ($reasons as $index => $title) {
            RejectionReason::query()->updateOrCreate(
                ['title' => $title],
                [
                    'sort_order' => ($index + 1) * 10,
                    'is_active' => true,
                ],
            );
        }
    }
}