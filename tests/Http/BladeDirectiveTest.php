<?php

namespace ProtoneMedia\AnalyticsEventTracking\Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\View;

class BladeDirectiveTest extends TestCase
{
    /** @test */
    public function it_has_a_blade_directive_that_compares_the_current_client_id_to_the_javascript_one()
    {
        Artisan::call('view:clear');

        View::addLocation(__DIR__);

        $this->assertStringContainsString(
            app('analytics-event-tracking.google.client-id'),
            view('javascriptView')->render()
        );
    }
}
