<?php

namespace App\Events;

use App\Models\SmsLog;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SmsQueued
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public SmsLog $smsLog)
    {
    }
}