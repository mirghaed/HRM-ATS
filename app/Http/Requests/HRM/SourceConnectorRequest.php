<?php

namespace App\Http\Requests\HRM;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SourceConnectorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return ($this->user()?->can('application_sources.create') || $this->user()?->can('application_sources.update')) ?? false;
    }

    public function rules(): array
    {
        return [
            'source_id' => ['required', 'exists:application_sources,id'],
            'driver' => ['required', 'string', 'max:150'],
            'mode' => ['required', Rule::in(['manual', 'api', 'imap', 'excel', 'webhook'])],
            'status' => ['required', Rule::in(['disabled', 'active', 'error'])],
            'endpoint_url' => ['nullable', 'url', 'max:500'],
            'encrypted_config' => ['nullable', 'string'],
            'last_error' => ['nullable', 'string'],
        ];
    }
}
