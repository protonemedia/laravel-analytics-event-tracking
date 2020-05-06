<?php

namespace ProtoneMedia\AnalyticsEventTracking\Tests;

use Illuminate\Support\Facades\Bus;
use ProtoneMedia\AnalyticsEventTracking\Jobs\SendEventToAnalytics;
use ProtoneMedia\AnalyticsEventTracking\Tests\Fakes\BroadcastMe;
use ProtoneMedia\AnalyticsEventTracking\Tests\Fakes\DontBroadcastMe;

class EventListenerTest extends TestCase
{
    /** @test */
    public function it_listen_for_events_that_should_be_broadcasted_to_analytics()
    {
        Bus::fake();

        event(new BroadcastMe);
        event(new DontBroadcastMe);

        Bus::assertDispatchedTimes(SendEventToAnalytics::class, 1);
    }

    /** @test */
    public function it_can_dispatch_jobs_on_a_dedicated_queue()
    {
        Bus::fake();

        config(['analytics-event-tracking.queue_name' => 'http']);

        event(new BroadcastMe);

        Bus::assertDispatched(SendEventToAnalytics::class, function ($job) {
            return $job->queue === 'http';
        });
    }
}
