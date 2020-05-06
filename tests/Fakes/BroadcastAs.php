<?php

namespace ProtoneMedia\AnalyticsEventTracking\Tests\Fakes;

use ProtoneMedia\AnalyticsEventTracking\Events\ShouldBroadcastToAnalytics;
use TheIconic\Tracking\GoogleAnalytics\Analytics;

class BroadcastAs implements ShouldBroadcastToAnalytics
{
    public function broadcastAnalyticsActionAs(Analytics $analytics)
    {
        return 'CustomEventAction';
    }
}
