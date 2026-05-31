<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\DepartmentRequest;
use App\Models\Department;
use App\Models\User;

class DepartmentController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Department::class);

        return view('hrm.departments.index', [
            'departments' => Department::query()->with('manager')->latest()->paginate(15),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Department::class);

        return view('hrm.departments.create', [
            'managers' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function store(DepartmentRequest $request)
    {
        Department::create($request->validated());

        return redirect()->route('hrm.departments.index')->with('success', 'دپارتمان با موفقیت ایجاد شد.');
    }

    public function show(Department $department)
    {
        $this->authorize('view', $department);

        return view('hrm.departments.show', [
            'department' => $department->load('manager', 'jobPositions'),
        ]);
    }

    public function edit(Department $department)
    {
        $this->authorize('update', $department);

        return view('hrm.departments.edit', [
            'department' => $department,
            'managers' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function update(DepartmentRequest $request, Department $department)
    {
        $this->authorize('update', $department);

        $department->update($request->validated());

        return redirect()->route('hrm.departments.index')->with('success', 'دپارتمان بروزرسانی شد.');
    }

    public function destroy(Department $department)
    {
        $this->authorize('delete', $department);

        $department->delete();

        return redirect()->route('hrm.departments.index')->with('success', 'دپارتمان حذف شد.');
    }
}