<?php

namespace App\Services\Sms;

class SmsTemplateRenderer
{
    public function render(string $template, array $variables): string
    {
        $result = $template;

        foreach ($variables as $key => $value) {
            $result = str_replace('{{'.$key.'}}', (string) $value, $result);
        }

        return $result;
    }
}