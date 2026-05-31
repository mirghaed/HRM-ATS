<?php

namespace App\Events;

use App\Models\Interview;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InterviewScheduled
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Interview $interview)
    {
    }
}