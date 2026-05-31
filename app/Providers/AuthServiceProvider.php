<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\Department;
use App\Models\HrmSetting;
use App\Models\Interview;
use App\Models\JobPosition;
use App\Policies\ApplicationPolicy;
use App\Policies\CandidatePolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\HrmSettingPolicy;
use App\Policies\InterviewPolicy;
use App\Policies\JobPositionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Department::class => DepartmentPolicy::class,
        JobPosition::class => JobPositionPolicy::class,
        Application::class => ApplicationPolicy::class,
        Interview::class => InterviewPolicy::class,
        Candidate::class => CandidatePolicy::class,
        HrmSetting::class => HrmSettingPolicy::class,
    ];
}