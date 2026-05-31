<?php

namespace App\Http\Requests\HRM;

use Illuminate\Foundation\Http\FormRequest;

class RecruitmentStatusTransitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('settings.manage') ?? false;
    }

    public function rules(): array
    {
        return [
            'from_status_id' => ['required', 'exists:recruitment_statuses,id', 'different:to_status_id'],
            'to_status_id' => ['required', 'exists:recruitment_statuses,id'],
            'allowed_roles' => ['nullable', 'string'],
            'requires_note' => ['nullable', 'boolean'],
            'requires_interview' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
