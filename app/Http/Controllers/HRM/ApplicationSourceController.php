<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\ApplicationSourceRequest;
use App\Models\ApplicationSource;

class ApplicationSourceController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->can('application_sources.view'), 403);

        return view('hrm.application_sources.index', [
            'sources' => ApplicationSource::query()->orderBy('name')->paginate(20),
        ]);
    }

    public function create()
    {
        abort_unless(auth()->user()?->can('application_sources.create'), 403);

        return view('hrm.application_sources.create');
    }

    public function store(ApplicationSourceRequest $request)
    {
        ApplicationSource::create($request->validated());

        return redirect()->route('hrm.application-sources.index')->with('success', 'منبع رزومه ایجاد شد.');
    }

    public function show(ApplicationSource $application_source)
    {
        abort_unless(auth()->user()?->can('application_sources.view'), 403);

        return view('hrm.application_sources.show', ['source' => $application_source->load('connectors')]);
    }

    public function edit(ApplicationSource $application_source)
    {
        abort_unless(auth()->user()?->can('application_sources.update'), 403);

        return view('hrm.application_sources.edit', ['source' => $application_source]);
    }

    public function update(ApplicationSourceRequest $request, ApplicationSource $application_source)
    {
        $application_source->update($request->validated());

        return redirect()->route('hrm.application-sources.index')->with('success', 'منبع رزومه بروزرسانی شد.');
    }

    public function destroy(ApplicationSource $application_source)
    {
        abort_unless(auth()->user()?->can('application_sources.delete'), 403);

        $application_source->delete();

        return redirect()->route('hrm.application-sources.index')->with('success', 'منبع رزومه حذف شد.');
    }
}