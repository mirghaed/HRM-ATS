<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\RejectionReasonRequest;
use App\Models\RejectionReason;

class RejectionReasonController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->can('settings.view'), 403);

        return view('hrm.rejection_reasons.index', [
            'reasons' => RejectionReason::query()->orderBy('sort_order')->orderBy('id')->paginate(20),
        ]);
    }

    public function create()
    {
        abort_unless(auth()->user()?->can('settings.manage'), 403);

        return view('hrm.rejection_reasons.create');
    }

    public function store(RejectionReasonRequest $request)
    {
        RejectionReason::query()->create($request->validated());

        return redirect()->route('hrm.rejection-reasons.index')->with('success', 'دلیل رد ایجاد شد.');
    }

    public function edit(RejectionReason $rejection_reason)
    {
        abort_unless(auth()->user()?->can('settings.manage'), 403);

        return view('hrm.rejection_reasons.edit', ['reason' => $rejection_reason]);
    }

    public function update(RejectionReasonRequest $request, RejectionReason $rejection_reason)
    {
        $rejection_reason->update($request->validated());

        return redirect()->route('hrm.rejection-reasons.index')->with('success', 'دلیل رد بروزرسانی شد.');
    }

    public function destroy(RejectionReason $rejection_reason)
    {
        abort_unless(auth()->user()?->can('settings.manage'), 403);

        $rejection_reason->delete();

        return redirect()->route('hrm.rejection-reasons.index')->with('success', 'دلیل رد حذف شد.');
    }
}
