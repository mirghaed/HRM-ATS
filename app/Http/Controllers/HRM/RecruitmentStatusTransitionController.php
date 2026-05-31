<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\RecruitmentStatusTransitionRequest;
use App\Models\RecruitmentStatusTransition;

class RecruitmentStatusTransitionController extends Controller
{
    public function store(RecruitmentStatusTransitionRequest $request)
    {
        $payload = $request->validated();
        $payload['allowed_roles'] = $this->parseRoles($payload['allowed_roles'] ?? null);
        $payload['requires_note'] = (bool) ($payload['requires_note'] ?? false);
        $payload['requires_interview'] = (bool) ($payload['requires_interview'] ?? false);
        $payload['is_active'] = (bool) ($payload['is_active'] ?? false);

        RecruitmentStatusTransition::query()->updateOrCreate(
            [
                'from_status_id' => $payload['from_status_id'],
                'to_status_id' => $payload['to_status_id'],
            ],
            $payload,
        );

        return back()->with('success', 'Transition ذخیره شد.');
    }

    public function destroy(RecruitmentStatusTransition $transition)
    {
        abort_unless(auth()->user()?->can('settings.manage'), 403);

        $transition->delete();

        return back()->with('success', 'Transition حذف شد.');
    }

    private function parseRoles(?string $roles): ?array
    {
        if ($roles === null || trim($roles) === '') {
            return null;
        }

        return collect(explode(',', $roles))
            ->map(fn (string $role) => trim($role))
            ->filter()
            ->values()
            ->all();
    }
}
