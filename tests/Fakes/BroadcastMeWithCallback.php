<?php

namespace ProtoneMedia\AnalyticsEventTracking\Tests\Fakes;

use ProtoneMedia\AnalyticsEventTracking\ShouldBroadcastToAnalytics;
use TheIconic\Tracking\GoogleAnalytics\Analytics;

class BroadcastMeWithCallback implements ShouldBroadcastToAnalytics
{
    public function withAnalytics(Analytics $analytics)
    {
        $analytics->setEventValue(100);
    }
}
