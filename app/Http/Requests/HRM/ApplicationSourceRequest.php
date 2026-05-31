<?php

namespace App\Http\Requests\HRM;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplicationSourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can($this->isMethod('POST') ? 'application_sources.create' : 'application_sources.update') ?? false;
    }

    public function rules(): array
    {
        $sourceId = $this->route('application_source')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:100', Rule::unique('application_sources', 'key')->ignore($sourceId)],
            'type' => ['required', Rule::in(['manual', 'website_form', 'job_board', 'referral', 'email', 'social', 'import', 'api', 'other'])],
            'supports_auto_import' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'config' => ['nullable', 'array'],
        ];
    }
}