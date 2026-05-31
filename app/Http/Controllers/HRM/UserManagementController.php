<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index()
    {
        $this->authorizeManageUsers();

        return view('hrm.users.index', [
            'users' => User::query()
                ->with(['roles:id,name', 'departments:id,name'])
                ->latest()
                ->paginate(20),
        ]);
    }

    public function create()
    {
        $this->authorizeManageUsers();

        return view('hrm.users.create', [
            'user' => null,
            'roles' => Role::query()->where('guard_name', 'web')->orderBy('name')->pluck('name'),
            'departments' => Department::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeManageUsers();

        $payload = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'mobile' => ['nullable', 'string', 'max:30', 'unique:users,mobile'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'role' => ['required', 'string', Rule::exists('roles', 'name')->where('guard_name', 'web')],
            'department_ids' => ['nullable', 'array'],
            'department_ids.*' => ['integer', 'exists:departments,id'],
        ]);

        $user = User::query()->create([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'mobile' => $payload['mobile'] ?? null,
            'password' => $payload['password'],
            'status' => $payload['status'],
            'email_verified_at' => now(),
        ]);

        $user->syncRoles([$payload['role']]);
        $this->syncDepartments($user, $payload['department_ids'] ?? []);

        return redirect()->route('hrm.users.index')->with('success', 'کاربر جدید ایجاد شد.');
    }

    public function edit(User $user)
    {
        $this->authorizeManageUsers();

        return view('hrm.users.edit', [
            'user' => $user->load(['roles:id,name', 'departments:id,name']),
            'roles' => Role::query()->where('guard_name', 'web')->orderBy('name')->pluck('name'),
            'departments' => Department::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeManageUsers();

        $payload = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'mobile' => ['nullable', 'string', 'max:30', Rule::unique('users', 'mobile')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'role' => ['required', 'string', Rule::exists('roles', 'name')->where('guard_name', 'web')],
            'department_ids' => ['nullable', 'array'],
            'department_ids.*' => ['integer', 'exists:departments,id'],
        ]);

        $updateData = [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'mobile' => $payload['mobile'] ?? null,
            'status' => $payload['status'],
        ];

        if (! empty($payload['password'])) {
            $updateData['password'] = $payload['password'];
        }

        $user->update($updateData);
        $user->syncRoles([$payload['role']]);
        $this->syncDepartments($user, $payload['department_ids'] ?? []);

        return redirect()->route('hrm.users.index')->with('success', 'اطلاعات کاربر بروزرسانی شد.');
    }

    public function destroy(User $user)
    {
        $this->authorizeManageUsers();

        if ((int) $user->id === (int) auth()->id()) {
            return back()->withErrors(['user' => 'امکان حذف حساب کاربری فعلی خودتان وجود ندارد.']);
        }

        $user->syncRoles([]);
        $user->departments()->detach();
        $user->delete();

        return redirect()->route('hrm.users.index')->with('success', 'کاربر حذف شد.');
    }

    private function authorizeManageUsers(): void
    {
        abort_unless(auth()->user()?->can('settings.manage'), 403);
    }

    private function syncDepartments(User $user, array $departmentIds): void
    {
        $syncData = [];
        foreach ($departmentIds as $departmentId) {
            $syncData[(int) $departmentId] = ['role' => 'member'];
        }

        $user->departments()->sync($syncData);
    }
}

