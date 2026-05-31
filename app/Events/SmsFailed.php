<?php

namespace App\Events;

use App\Models\SmsLog;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SmsFailed
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public SmsLog $smsLog, public string $error)
    {
    }
}