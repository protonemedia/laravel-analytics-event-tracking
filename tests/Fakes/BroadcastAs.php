<?php

namespace ProtoneMedia\AnalyticsEventTracking\Tests\Fakes;

use ProtoneMedia\AnalyticsEventTracking\ShouldBroadcastToAnalytics;
use TheIconic\Tracking\GoogleAnalytics\Analytics;

class BroadcastAs implements ShouldBroadcastToAnalytics
{
    public function broadcastAnalyticsActionAs(Analytics $analytics)
    {
        return 'CustomEventAction';
    }
}
