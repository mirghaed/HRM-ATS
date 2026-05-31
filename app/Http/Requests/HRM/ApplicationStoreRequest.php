<?php

namespace App\Http\Requests\HRM;

use App\Services\HRM\HrmSettingService;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('applications.create') ?? false;
    }

    public function rules(): array
    {
        $settings = app(HrmSettingService::class);
        $maxResumeSizeKb = (int) $settings->get('upload.max_resume_size_kb', 10240);
        $allowedExt = 'pdf';

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'job_position_id' => ['nullable', 'exists:job_positions,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'source_id' => ['required', 'exists:application_sources,id'],
            'source_reference' => ['nullable', 'string', 'max:255'],
            'expected_salary_min' => ['nullable', 'integer', 'min:0'],
            'expected_salary_max' => ['nullable', 'integer', 'min:0', 'gte:expected_salary_min'],
            'cover_letter' => ['nullable', 'string'],
            'resume' => ['nullable', 'file', 'mimes:'.$allowedExt, 'max:'.$maxResumeSizeKb],
        ];
    }
}
