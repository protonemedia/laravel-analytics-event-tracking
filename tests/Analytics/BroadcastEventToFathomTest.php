<?php

namespace ProtoneMedia\AnalyticsEventTracking\Tests\Analytics;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use ProtoneMedia\AnalyticsEventTracking\Analytics\BroadcastEventToFathom;
use ProtoneMedia\AnalyticsEventTracking\Tests\Fakes\BroadcastAs;
use ProtoneMedia\AnalyticsEventTracking\Tests\Fakes\BroadcastMe;
use ProtoneMedia\AnalyticsEventTracking\Tests\Fakes\BroadcastMeWithRequestCallback;
use ProtoneMedia\AnalyticsEventTracking\Tests\TestCase;

class BroadcastEventToFathomTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Http::fake();

        config([
            'analytics-event-tracking.fathom.api_token' => 'secret',
            'analytics-event-tracking.fathom.site_id'   => 1337,
        ]);
    }

    /** @test */
    public function it_sends_the_basename_as_event_action()
    {
        $broadcaster = new BroadcastEventToFathom;
        $broadcaster->handle(new BroadcastMe);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('Authorization', 'Bearer secret')
             && $request->url() == 'https://api.usefathom.com/v1/sites/1337/events'
             && $request['name'] == 'broadcast-me';
        });
    }

    /** @test */
    public function it_sends_a_custom_event_action()
    {
        $broadcaster = new BroadcastEventToFathom;
        $broadcaster->handle(new BroadcastAs);

        Http::assertSent(function (Request $request) {
            return $request['name'] == 'CustomEventAction';
        });
    }

    /** @test */
    public function it_has_an_optional_callback_to_interact_with_analytics()
    {
        $broadcaster = new BroadcastEventToFathom;
        $broadcaster->handle(new BroadcastMeWithRequestCallback);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('User-Agent', 'ProtoneMedia');
        });
    }

    /** @test */
    public function it_can_interact_with_the_analytics_instance()
    {
        $broadcaster = new BroadcastEventToFathom;

        $broadcaster->withAnalytics(fn ($analytics) => $analytics->withUserAgent('ProtoneMedia'));
        $broadcaster->handle(new BroadcastMe);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('User-Agent', 'ProtoneMedia');
        });
    }
}
