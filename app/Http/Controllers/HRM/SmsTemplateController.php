<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\SmsTemplateRequest;
use App\Models\SmsTemplate;

class SmsTemplateController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->can('sms_templates.view'), 403);

        return view('hrm.sms_templates.index', [
            'templates' => SmsTemplate::query()->latest()->paginate(20),
        ]);
    }

    public function create()
    {
        abort_unless(auth()->user()?->can('sms_templates.create'), 403);

        return view('hrm.sms_templates.create');
    }

    public function store(SmsTemplateRequest $request)
    {
        SmsTemplate::create($request->validated());

        return redirect()->route('hrm.sms-templates.index')->with('success', 'قالب پیامک ایجاد شد.');
    }

    public function show(SmsTemplate $sms_template)
    {
        abort_unless(auth()->user()?->can('sms_templates.view'), 403);

        return view('hrm.sms_templates.show', ['template' => $sms_template]);
    }

    public function edit(SmsTemplate $sms_template)
    {
        abort_unless(auth()->user()?->can('sms_templates.update'), 403);

        return view('hrm.sms_templates.edit', ['template' => $sms_template]);
    }

    public function update(SmsTemplateRequest $request, SmsTemplate $sms_template)
    {
        $sms_template->update($request->validated());

        return redirect()->route('hrm.sms-templates.index')->with('success', 'قالب پیامک بروزرسانی شد.');
    }

    public function destroy(SmsTemplate $sms_template)
    {
        abort_unless(auth()->user()?->can('sms_templates.delete'), 403);

        $sms_template->delete();

        return redirect()->route('hrm.sms-templates.index')->with('success', 'قالب پیامک حذف شد.');
    }
}