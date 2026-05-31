<?php

namespace Database\Seeders;

use App\Models\RecruitmentStatus;
use App\Models\RecruitmentStatusTransition;
use Illuminate\Database\Seeder;

class RecruitmentStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['key' => 'received', 'title' => 'دریافت رزومه', 'color' => '#64748b', 'sort_order' => 10, 'is_default' => true],
            ['key' => 'screening', 'title' => 'بررسی اولیه HR', 'color' => '#0ea5e9', 'sort_order' => 20],
            ['key' => 'department_review', 'title' => 'بررسی دپارتمان', 'color' => '#f59e0b', 'sort_order' => 30],
            ['key' => 'interview', 'title' => 'مصاحبه', 'color' => '#8b5cf6', 'sort_order' => 40, 'can_schedule_interview' => true],
            ['key' => 'offer', 'title' => 'پیشنهاد همکاری', 'color' => '#14b8a6', 'sort_order' => 50],
            ['key' => 'hired', 'title' => 'استخدام', 'color' => '#22c55e', 'sort_order' => 60, 'is_terminal' => true, 'is_success' => true],
            ['key' => 'rejected', 'title' => 'رد شده', 'color' => '#ef4444', 'sort_order' => 70, 'is_terminal' => true],
        ];

        foreach ($statuses as $status) {
            RecruitmentStatus::query()->updateOrCreate(['key' => $status['key']], array_merge([
                'description' => null,
                'is_default' => false,
                'is_terminal' => false,
                'is_success' => false,
                'requires_note' => false,
                'can_schedule_interview' => false,
                'notify_candidate' => false,
                'notify_department_manager' => false,
                'notify_interviewer' => false,
            ], $status));
        }

        $keys = RecruitmentStatus::query()->orderBy('sort_order')->pluck('id', 'key');

        $transitions = [
            ['from' => 'received', 'to' => 'screening'],
            ['from' => 'screening', 'to' => 'department_review'],
            ['from' => 'department_review', 'to' => 'interview'],
            ['from' => 'interview', 'to' => 'offer'],
            ['from' => 'offer', 'to' => 'hired'],
            ['from' => 'screening', 'to' => 'rejected'],
            ['from' => 'department_review', 'to' => 'rejected'],
            ['from' => 'interview', 'to' => 'rejected'],
        ];

        foreach ($transitions as $transition) {
            RecruitmentStatusTransition::query()->updateOrCreate(
                ['from_status_id' => $keys[$transition['from']], 'to_status_id' => $keys[$transition['to']]],
                [
                    'allowed_roles' => ['Super Admin', 'HR Manager', 'HR Staff / Recruiter'],
                    'requires_note' => false,
                    'requires_interview' => false,
                    'is_active' => true,
                ],
            );
        }
    }
}