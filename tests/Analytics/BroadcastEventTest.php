<?php

namespace ProtoneMedia\AnalyticsEventTracking\Tests\Analytics;

use ProtoneMedia\AnalyticsEventTracking\Analytics\BroadcastEvent;
use ProtoneMedia\AnalyticsEventTracking\Tests\Fakes\BroadcastAs;
use ProtoneMedia\AnalyticsEventTracking\Tests\Fakes\BroadcastMe;
use ProtoneMedia\AnalyticsEventTracking\Tests\Fakes\BroadcastMeWithCallback;
use ProtoneMedia\AnalyticsEventTracking\Tests\TestCase;
use TheIconic\Tracking\GoogleAnalytics\Analytics;

class BroadcastEventTest extends TestCase
{
    /** @test */
    public function it_sends_the_basename_as_event_action()
    {
        $analytics = $this->mock(Analytics::class);

        $analytics->shouldReceive('setEventAction')->with('BroadcastMe');
        $analytics->shouldReceive('sendEvent');

        $broadcaster = new BroadcastEvent($analytics);
        $broadcaster->handle(new BroadcastMe);
    }

    /** @test */
    public function it_sends_a_custom_event_action()
    {
        $analytics = $this->mock(Analytics::class);

        $analytics->shouldReceive('setEventAction')->with('CustomEventAction');
        $analytics->shouldReceive('sendEvent');

        $broadcaster = new BroadcastEvent($analytics);
        $broadcaster->handle(new BroadcastAs);
    }

    /** @test */
    public function it_has_an_optional_callback_to_interact_with_analytics()
    {
        $analytics = $this->mock(Analytics::class);

        $analytics->shouldReceive('setEventAction');
        $analytics->shouldReceive('setEventValue')->with(100);
        $analytics->shouldReceive('sendEvent');

        $broadcaster = new BroadcastEvent($analytics);
        $broadcaster->handle(new BroadcastMeWithCallback);
    }

    /** @test */
    public function it_can_interact_with_the_analytics_instance()
    {
        $analytics = $this->mock(Analytics::class)
            ->shouldReceive('setClientId')
            ->with(1337)
            ->getMock();

        $broadcaster = new BroadcastEvent($analytics);

        $broadcaster->withAnalytics(fn ($analytics) => $analytics->setClientId(1337));
    }
}
