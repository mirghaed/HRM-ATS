<?php

namespace App\Events;

use App\Models\Application;
use App\Models\ApplicationStatusHistory;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusChanged
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Application $application,
        public ApplicationStatusHistory $history,
    ) {
    }
}