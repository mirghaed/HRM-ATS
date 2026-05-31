<?php

namespace App\Http\Requests\HRM;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (! $this->user()) {
            return false;
        }

        if ($this->isMethod('POST')) {
            return $this->user()->can('interviews.create');
        }

        return $this->user()->can('interviews.update');
    }

    public function rules(): array
    {
        return [
            'application_id' => ['required', 'exists:applications,id'],
            'interviewer_id' => ['required', 'exists:users,id'],
            'type' => ['required', Rule::in(['onsite', 'online', 'phone'])],
            'start_at' => ['required', 'date', 'after:now'],
            'end_at' => ['nullable', 'date', 'after:start_at'],
            'location_title' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'online_meeting_url' => ['nullable', 'url', 'max:500'],
            'description' => ['nullable', 'string'],
            'send_sms_to_candidate' => ['nullable', 'boolean'],
            'status' => ['nullable', Rule::in(['scheduled', 'completed', 'cancelled', 'no_show', 'rescheduled'])],
            'score' => ['nullable', 'integer', 'between:0,100'],
            'result_note' => ['nullable', 'string'],
        ];
    }
}
