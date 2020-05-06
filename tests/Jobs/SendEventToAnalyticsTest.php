<?php

namespace ProtoneMedia\AnalyticsEventTracking\Jobs;

use ProtoneMedia\AnalyticsEventTracking\Analytics\EventBroadcaster;
use ProtoneMedia\AnalyticsEventTracking\Tests\Fakes\BroadcastMe;
use ProtoneMedia\AnalyticsEventTracking\Tests\TestCase;
use TheIconic\Tracking\GoogleAnalytics\Analytics;

class SendEventToAnalyticsTest extends TestCase
{
    /** @test */
    public function it_uses_the_analytics_broadcaster_to_send_the_event()
    {
        $event = new BroadcastMe;

        $this->mock(EventBroadcaster::class)
            ->shouldReceive('handle')
            ->withArgs(fn ($eventAsArgument) => $eventAsArgument == $event);

        SendEventToAnalytics::dispatch($event);
    }

    /** @test */
    public function it_sets_the_client_id_if_given_as_constructor_argument()
    {
        $event = new BroadcastMe;

        $analytics = $this->mock(Analytics::class)
            ->shouldReceive('setClientId')
            ->with('1337')
            ->andReturnTrue()
            ->getMock();

        $this->mock(EventBroadcaster::class)
            ->shouldReceive('handle')
            ->shouldReceive('withAnalytics')
            ->withArgs(fn ($callable) => $callable($analytics));

        SendEventToAnalytics::dispatch($event, '1337');
    }
}
