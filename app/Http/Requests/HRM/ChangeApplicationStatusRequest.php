<?php

namespace App\Http\Requests\HRM;

use Illuminate\Foundation\Http\FormRequest;

class ChangeApplicationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('applications.change_status') ?? false;
    }

    public function rules(): array
    {
        return [
            'status_id' => ['required', 'exists:recruitment_statuses,id'],
            'note' => ['nullable', 'string'],
        ];
    }
}