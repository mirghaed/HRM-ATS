<?php

namespace Tests\Feature;

use Database\Seeders\ApplicationSourceSeeder;
use Database\Seeders\CareerLandingSeeder;
use Database\Seeders\HrmSettingSeeder;
use Database\Seeders\RecruitmentStatusSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(ApplicationSourceSeeder::class);
        $this->seed(RecruitmentStatusSeeder::class);
        $this->seed(HrmSettingSeeder::class);
        $this->seed(CareerLandingSeeder::class);
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        $this->get('/')->assertOk();
    }
}
