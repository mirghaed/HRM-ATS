<?php

namespace App\Http\Requests\HRM;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplicationNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('notes.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['internal_note', 'phone_call_note', 'interview_note', 'salary_note', 'rejection_note', 'system_note'])],
            'body' => ['required', 'string'],
            'visibility' => ['required', Rule::in(['hr_only', 'department', 'all_internal'])],
            'is_pinned' => ['nullable', 'boolean'],
        ];
    }
}