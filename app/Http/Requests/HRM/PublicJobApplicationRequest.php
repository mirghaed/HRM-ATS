<?php

namespace App\Http\Requests\HRM;

use App\Services\Captcha\NumericCaptchaService;
use App\Services\HRM\HrmSettingService;
use Illuminate\Foundation\Http\FormRequest;

class PublicJobApplicationRequest extends FormRequest
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
            'full_name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'expected_salary' => ['nullable', 'integer', 'min:0'],
            'cover_letter' => ['nullable', 'string'],
            'resume' => ['required', 'file', 'mimes:'.$allowedExt, 'max:'.$maxResumeSizeKb],
            'answers' => ['nullable', 'array'],
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
