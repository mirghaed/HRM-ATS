<?php

namespace App\Services\Captcha;

use App\Support\PersianDigit;
use Gregwar\Captcha\CaptchaBuilder;

class NumericCaptchaService
{
    public const SESSION_KEY = 'public_forms.numeric_captcha';

    public function generate(int $length = 5): array
    {
        $code = $this->generateCode($length);
        $builder = new CaptchaBuilder(PersianDigit::toPersian($code));

        $builder->setDistortion(true);
        $builder->setMaxBehindLines(2);
        $builder->setMaxFrontLines(1);
        $builder->setTextColor(20, 30, 48);
        $builder->setBackgroundColor(245, 248, 252);
        $builder->setImageType('png');

        $font = $this->detectFont();
        $builder->build(170, 58, $font);

        session()->put(self::SESSION_KEY, $code);

        return [
            'content' => $builder->get(),
            'mime' => 'image/'.$builder->getImageType(),
            'code_persian' => PersianDigit::toPersian($code),
        ];
    }

    public function normalize(?string $input): string
    {
        return PersianDigit::onlyDigits($input);
    }

    public function validate(?string $input): bool
    {
        $stored = (string) session()->get(self::SESSION_KEY, '');
        $normalized = $this->normalize($input);

        return $stored !== '' && hash_equals($stored, $normalized);
    }

    public function forget(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    private function detectFont(): ?string
    {
        $candidates = [
            'C:/Windows/Fonts/arial.ttf',
            'C:/Windows/Fonts/tahoma.ttf',
        ];

        foreach ($candidates as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    private function generateCode(int $length): string
    {
        $buffer = '';
        for ($i = 0; $i < $length; $i++) {
            $buffer .= (string) random_int(0, 9);
        }

        return $buffer;
    }
}
