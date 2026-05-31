<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Jobs\HRM\SendSmsJob;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationSmsController extends Controller
{
    public function send(Request $request, Application $application)
    {
        abort_unless($request->user()?->can('sms.send'), 403);
        $this->authorize('view', $application);

        $validated = $request->validate([
            'template_id' => ['required', 'exists:sms_templates,id'],
        ]);

        SendSmsJob::dispatch(
            applicationId: $application->id,
            templateId: (int) $validated['template_id'],
            triggeredByUserId: auth()->id(),
        );

        return back()->with('success', 'پیامک در صف ارسال قرار گرفت.');
    }
}
