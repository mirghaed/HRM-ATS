<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\ApplicationNoteRequest;
use App\Models\Application;

class ApplicationNoteController extends Controller
{
    public function store(ApplicationNoteRequest $request, Application $application)
    {
        $this->authorize('view', $application);

        $application->notes()->create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'یادداشت ثبت شد.');
    }
}
