<?php

namespace App\Services\HRM;

use App\Events\ApplicationStatusChanged;
use App\Events\CandidateHired;
use App\Events\CandidateRejected;
use App\Models\Application;
use App\Models\ApplicationStatusHistory;
use App\Models\RecruitmentStatus;
use App\Models\RecruitmentStatusTransition;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApplicationStatusService
{
    public function changeStatus(Application $application, RecruitmentStatus $toStatus, ?string $note = null, array $meta = []): Application
    {
        $this->guardTransition($application, $toStatus, $note);

        return DB::transaction(function () use ($application, $toStatus, $note, $meta) {
            $fromStatusId = $application->current_status_id;

            $application->update([
                'current_status_id' => $toStatus->id,
                'last_status_changed_at' => now(),
            ]);

            $history = ApplicationStatusHistory::create([
                'application_id' => $application->id,
                'from_status_id' => $fromStatusId,
                'to_status_id' => $toStatus->id,
                'changed_by' => auth()->id(),
                'note' => $note,
                'meta' => $meta,
            ]);

            event(new ApplicationStatusChanged($application->fresh(), $history));

            if ($toStatus->is_success) {
                event(new CandidateHired($application->fresh()));
            } elseif ($toStatus->key === 'rejected') {
                event(new CandidateRejected($application->fresh()));
            }

            return $application;
        });
    }

    private function guardTransition(Application $application, RecruitmentStatus $toStatus, ?string $note): void
    {
        $fromStatusId = $application->current_status_id;

        if ($fromStatusId) {
            $transition = RecruitmentStatusTransition::query()
                ->where('from_status_id', $fromStatusId)
                ->where('to_status_id', $toStatus->id)
                ->where('is_active', true)
                ->first();

            if (! $transition) {
                throw ValidationException::withMessages([
                    'status_id' => 'انتقال وضعیت انتخاب‌شده مجاز نیست.',
                ]);
            }

            if ($transition->allowed_roles) {
                $allowed = collect($transition->allowed_roles)->filter()->values();
                $hasAllowedRole = $allowed->isEmpty() || auth()->user()?->hasAnyRole($allowed->all());
                if (! $hasAllowedRole) {
                    throw ValidationException::withMessages([
                        'status_id' => 'نقش شما اجازه این تغییر وضعیت را ندارد.',
                    ]);
                }
            }

            if ($transition->requires_note && blank($note)) {
                throw ValidationException::withMessages([
                    'note' => 'برای این انتقال ثبت توضیح الزامی است.',
                ]);
            }

            if ($transition->requires_interview && ! $application->interviews()->exists()) {
                throw ValidationException::withMessages([
                    'status_id' => 'قبل از این انتقال باید مصاحبه برای کارجو ثبت شده باشد.',
                ]);
            }
        }

        if ($toStatus->requires_note && blank($note)) {
            throw ValidationException::withMessages([
                'note' => 'برای وضعیت مقصد ثبت توضیح الزامی است.',
            ]);
        }

    }
}
