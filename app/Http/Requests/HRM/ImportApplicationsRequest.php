<?php

namespace App\Http\Requests\HRM;

use Illuminate\Foundation\Http\FormRequest;

class ImportApplicationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('imports.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'source_id' => ['required', 'exists:application_sources,id'],
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx', 'max:20480'],
        ];
    }
}
