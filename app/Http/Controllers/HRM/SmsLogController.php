<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;

class SmsLogController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->can('sms_logs.view'), 403);

        return view('hrm.sms_logs.index', [
            'smsLogs' => SmsLog::query()->with('candidate', 'application', 'template')->latest()->paginate(30),
        ]);
    }
}