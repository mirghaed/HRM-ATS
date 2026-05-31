<?php

namespace App\Http\Requests\HRM;

use App\Models\RecruitmentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RecruitmentStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('settings.manage') ?? false;
    }

    public function rules(): array
    {
        /** @var RecruitmentStatus|null $status */
        $status = $this->route('recruitment_status');

        return [
            'key' => ['required', 'string', 'max:100', 'alpha_dash', Rule::unique('recruitment_statuses', 'key')->ignore($status?->id)],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'color' => ['nullable', 'string', 'max:30'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'sms_template_id' => ['nullable', 'exists:sms_templates,id'],
            'is_default' => ['nullable', 'boolean'],
            'is_terminal' => ['nullable', 'boolean'],
            'is_success' => ['nullable', 'boolean'],
            'requires_note' => ['nullable', 'boolean'],
            'can_schedule_interview' => ['nullable', 'boolean'],
            'notify_candidate' => ['nullable', 'boolean'],
            'notify_department_manager' => ['nullable', 'boolean'],
            'notify_interviewer' => ['nullable', 'boolean'],
        ];
    }
}
