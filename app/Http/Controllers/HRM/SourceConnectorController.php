<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\SourceConnectorRequest;
use App\Models\ApplicationSource;
use App\Models\SourceConnector;

class SourceConnectorController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->can('application_sources.view'), 403);

        return view('hrm.source_connectors.index', [
            'connectors' => SourceConnector::query()
                ->with(['source:id,name,key', 'creator:id,name'])
                ->latest('id')
                ->paginate(20),
        ]);
    }

    public function create()
    {
        abort_unless(auth()->user()?->can('application_sources.create'), 403);

        return view('hrm.source_connectors.create', [
            'sources' => ApplicationSource::query()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'key']),
        ]);
    }

    public function store(SourceConnectorRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        SourceConnector::query()->create($data);

        return redirect()->route('hrm.source-connectors.index')->with('success', 'اتصال منبع رزومه ایجاد شد.');
    }

    public function edit(SourceConnector $source_connector)
    {
        abort_unless(auth()->user()?->can('application_sources.update'), 403);

        return view('hrm.source_connectors.edit', [
            'connector' => $source_connector,
            'sources' => ApplicationSource::query()->orderBy('name')->get(['id', 'name', 'key']),
        ]);
    }

    public function update(SourceConnectorRequest $request, SourceConnector $source_connector)
    {
        $source_connector->update($request->validated());

        return redirect()->route('hrm.source-connectors.index')->with('success', 'اتصال منبع رزومه بروزرسانی شد.');
    }

    public function destroy(SourceConnector $source_connector)
    {
        abort_unless(auth()->user()?->can('application_sources.delete'), 403);

        $source_connector->delete();

        return redirect()->route('hrm.source-connectors.index')->with('success', 'اتصال منبع رزومه حذف شد.');
    }
}
