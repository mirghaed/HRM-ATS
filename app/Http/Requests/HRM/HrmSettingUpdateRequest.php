<?php

namespace App\Http\Requests\HRM;

use Illuminate\Foundation\Http\FormRequest;

class HrmSettingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('settings.manage') ?? false;
    }

    public function rules(): array
    {
        return [
            'settings' => ['required', 'array'],
            'settings.*.key' => ['required', 'string', 'max:150'],
            'settings.*.value' => ['nullable'],
            'settings.*.type' => ['required', 'string', 'max:50'],
            'settings.*.group' => ['required', 'string', 'max:100'],
            'settings.*.is_public' => ['nullable', 'boolean'],
        ];
    }
}