<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\RecruitmentStatusRequest;
use App\Models\RecruitmentStatus;
use App\Models\SmsTemplate;

class RecruitmentStatusController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->can('settings.view'), 403);

        return view('hrm.recruitment_statuses.index', [
            'statuses' => RecruitmentStatus::query()
                ->withCount(['transitionsFrom', 'applications'])
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function create()
    {
        abort_unless(auth()->user()?->can('settings.manage'), 403);

        return view('hrm.recruitment_statuses.create', [
            'smsTemplates' => SmsTemplate::query()->where('is_active', true)->orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function store(RecruitmentStatusRequest $request)
    {
        $payload = $this->normalizePayload($request->validated());

        if (($payload['is_default'] ?? false) === true) {
            RecruitmentStatus::query()->update(['is_default' => false]);
        }

        RecruitmentStatus::query()->create($payload);

        return redirect()->route('hrm.recruitment-statuses.index')->with('success', 'وضعیت استخدام ایجاد شد.');
    }

    public function edit(RecruitmentStatus $recruitment_status)
    {
        abort_unless(auth()->user()?->can('settings.manage'), 403);

        return view('hrm.recruitment_statuses.edit', [
            'status' => $recruitment_status->load([
                'transitionsFrom.toStatus:id,key,title',
                'transitionsFrom',
            ]),
            'allStatuses' => RecruitmentStatus::query()->orderBy('sort_order')->get(['id', 'title', 'key']),
            'smsTemplates' => SmsTemplate::query()->where('is_active', true)->orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function update(RecruitmentStatusRequest $request, RecruitmentStatus $recruitment_status)
    {
        $payload = $this->normalizePayload($request->validated());

        if (($payload['is_default'] ?? false) === true) {
            RecruitmentStatus::query()
                ->whereKeyNot($recruitment_status->id)
                ->update(['is_default' => false]);
        }

        $recruitment_status->update($payload);

        return redirect()->route('hrm.recruitment-statuses.index')->with('success', 'وضعیت استخدام بروزرسانی شد.');
    }

    public function destroy(RecruitmentStatus $recruitment_status)
    {
        abort_unless(auth()->user()?->can('settings.manage'), 403);

        if ($recruitment_status->applications()->exists()) {
            return back()->withErrors(['status' => 'این وضعیت به Applicationها متصل است و قابل حذف نیست.']);
        }

        $recruitment_status->delete();

        return redirect()->route('hrm.recruitment-statuses.index')->with('success', 'وضعیت استخدام حذف شد.');
    }

    private function normalizePayload(array $payload): array
    {
        foreach ([
            'is_default',
            'is_terminal',
            'is_success',
            'requires_note',
            'can_schedule_interview',
            'notify_candidate',
            'notify_department_manager',
            'notify_interviewer',
        ] as $field) {
            $payload[$field] = (bool) ($payload[$field] ?? false);
        }

        $payload['sort_order'] = (int) ($payload['sort_order'] ?? 0);

        return $payload;
    }
}
