<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\ChangeApplicationStatusRequest;
use App\Models\Application;
use App\Models\RecruitmentStatus;
use App\Services\HRM\ApplicationStatusService;

class ApplicationStatusController extends Controller
{
    public function change(ChangeApplicationStatusRequest $request, Application $application, ApplicationStatusService $statusService)
    {
        $this->authorize('update', $application);

        $toStatus = RecruitmentStatus::query()->findOrFail($request->integer('status_id'));

        $statusService->changeStatus(
            application: $application,
            toStatus: $toStatus,
            note: $request->string('note')->toString(),
            meta: ['trigger' => 'manual'],
        );

        return back()->with('success', 'وضعیت رزومه تغییر کرد.');
    }
}
