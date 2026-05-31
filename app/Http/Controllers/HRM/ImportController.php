<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\ImportApplicationsRequest;
use App\Models\ApplicationSource;
use App\Models\ImportRun;
use App\Services\HRM\ResumeImportManager;

class ImportController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->can('imports.view'), 403);

        return view('hrm.imports.index', [
            'runs' => ImportRun::query()->with('source', 'creator')->latest()->paginate(20),
        ]);
    }

    public function create()
    {
        abort_unless(auth()->user()?->can('imports.create'), 403);

        return view('hrm.imports.create', [
            'sources' => ApplicationSource::query()
                ->where(function ($query) {
                    $query->where('type', 'import')->orWhere('key', 'excel_import');
                })
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(ImportApplicationsRequest $request, ResumeImportManager $manager)
    {
        $path = $request->file('file')->store('temp/hrm-imports', 'local');
        $source = ApplicationSource::query()->findOrFail($request->integer('source_id'));

        $run = $manager->queueImport($source, $path, auth()->id());

        return redirect()->route('hrm.imports.show', $run)->with('success', 'ایمپورت در صف پردازش قرار گرفت.');
    }

    public function show(ImportRun $import)
    {
        abort_unless(auth()->user()?->can('imports.view'), 403);

        return view('hrm.imports.show', [
            'run' => $import->load('source', 'items.application.candidate'),
        ]);
    }

    public function retry(ImportRun $import, ResumeImportManager $manager)
    {
        abort_unless(auth()->user()?->can('imports.retry'), 403);

        $manager->retry($import);

        return redirect()->route('hrm.imports.show', $import)->with('success', 'ایمپورت مجددا در صف پردازش قرار گرفت.');
    }
}
