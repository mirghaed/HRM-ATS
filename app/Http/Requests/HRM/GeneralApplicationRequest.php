<?php

namespace App\Http\Requests\HRM;

use App\Services\Captcha\NumericCaptchaService;
use App\Services\HRM\HrmSettingService;
use Illuminate\Foundation\Http\FormRequest;

class GeneralApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $settings = app(HrmSettingService::class);
        $captchaService = app(NumericCaptchaService::class);
        $maxResumeSizeKb = (int) $settings->get('upload.max_resume_size_kb', 10240);
        $allowedExt = 'pdf';

        return [
            'full_name' => ['required', 'string', 'min:3', 'max:120'],
            'mobile' => ['required', 'string', 'min:10', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'job_position_id' => ['nullable', 'exists:job_positions,id'],
            'preferred_department_id' => ['nullable', 'exists:departments,id'],
            'preferred_job_title' => ['nullable', 'string', 'max:150'],
            'expected_salary' => ['nullable', 'integer', 'min:0'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'cover_letter' => ['nullable', 'string', 'max:3000'],
            'resume' => ['required', 'file', 'mimes:'.$allowedExt, 'max:'.$maxResumeSizeKb],
            'captcha' => [
                'required',
                function (string $attribute, mixed $value, \Closure $fail) use ($captchaService): void {
                    if (! $captchaService->validate((string) $value)) {
                        $fail('کد امنیتی وارد شده صحیح نیست.');
                    }
                },
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $captchaService = app(NumericCaptchaService::class);

        $this->merge([
            'captcha' => $captchaService->normalize((string) $this->input('captcha')),
        ]);
    }
}
