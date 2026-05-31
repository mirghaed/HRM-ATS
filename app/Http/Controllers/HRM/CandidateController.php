<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Candidate::class);

        $query = Candidate::query();

        if ($search = trim((string) $request->get('q'))) {
            $query->where('full_name', 'like', "%{$search}%")
                ->orWhere('mobile', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        return view('hrm.candidates.index', [
            'candidates' => $query->latest()->paginate(20)->withQueryString(),
        ]);
    }

    public function show(Candidate $candidate)
    {
        $this->authorize('view', $candidate);

        return view('hrm.candidates.show', [
            'candidate' => $candidate->load('applications.jobPosition', 'files'),
        ]);
    }

    public function edit(Candidate $candidate)
    {
        $this->authorize('update', $candidate);

        return view('hrm.candidates.edit', ['candidate' => $candidate]);
    }

    public function update(Request $request, Candidate $candidate)
    {
        $this->authorize('update', $candidate);

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'current_job_title' => ['nullable', 'string', 'max:255'],
            'current_company' => ['nullable', 'string', 'max:255'],
        ]);

        $data['city'] = 'تهران';
        $candidate->update($data);

        return redirect()->route('hrm.candidates.show', $candidate)->with('success', 'اطلاعات کارجو بروزرسانی شد.');
    }
}
