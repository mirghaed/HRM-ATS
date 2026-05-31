<?php

namespace App\Http\Requests\HRM;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SmsTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can($this->isMethod('POST') ? 'sms_templates.create' : 'sms_templates.update') ?? false;
    }

    public function rules(): array
    {
        $templateId = $this->route('sms_template')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:100', Rule::unique('sms_templates', 'key')->ignore($templateId)],
            'provider_template_id' => ['nullable', 'string', 'max:100'],
            'body_preview' => ['nullable', 'string'],
            'variables' => ['nullable', 'array'],
            'is_active' => ['nullable', 'boolean'],
            'auto_send' => ['nullable', 'boolean'],
        ];
    }
}