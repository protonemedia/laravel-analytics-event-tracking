<?php

namespace ProtoneMedia\AnalyticsEventTracking\Tests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
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
    public function it_will_not_dispatch_if_disabled()
    {
        Bus::fake();

        config([ 'analytics-event-tracking.is_disabled' => true ]);

        event(new BroadcastMe);

        Bus::assertNotDispatched(SendEventToAnalytics::class);

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

    private function authenticateUser()
    {
        Auth::login(
            User::unguarded(fn () => new User(['id' => 1337]))
        );
    }

    /** @test */
    public function it_sets_the_user_id_if_authenticated_and_configuration_setting_is_enabled()
    {
        config(['analytics-event-tracking.send_user_id' => true]);

        $this->authenticateUser();

        Bus::fake();

        event(new BroadcastMe);

        Bus::assertDispatched(SendEventToAnalytics::class, function ($job) {
            return $job->userId == 1337;
        });
    }

    /** @test */
    public function it_doesnt_set_the_user_id_if_authenticated_and_configuration_setting_is_disabled()
    {
        config(['analytics-event-tracking.send_user_id' => false]);

        $this->authenticateUser();

        Bus::fake();

        event(new BroadcastMe);

        Bus::assertDispatched(SendEventToAnalytics::class, function ($job) {
            return is_null($job->userId);
        });
    }

    /** @test */
    public function it_doesnt_set_the_user_id_if_not_authenticated()
    {
        config(['analytics-event-tracking.send_user_id' => true]);

        Bus::fake();

        event(new BroadcastMe);

        Bus::assertDispatched(SendEventToAnalytics::class, function ($job) {
            return is_null($job->userId);
        });
    }
}
