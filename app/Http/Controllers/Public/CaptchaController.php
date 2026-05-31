<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\Captcha\NumericCaptchaService;
use Illuminate\Http\Response;

class CaptchaController extends Controller
{
    public function image(NumericCaptchaService $captchaService): Response
    {
        $captcha = $captchaService->generate();

        return response($captcha['content'], 200, [
            'Content-Type' => $captcha['mime'],
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => 'Fri, 01 Jan 1990 00:00:00 GMT',
        ]);
    }
}

