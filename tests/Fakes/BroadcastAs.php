<?php

namespace ProtoneMedia\AnalyticsEventTracking\Tests\Fakes;

use ProtoneMedia\AnalyticsEventTracking\ShouldBroadcastToAnalytics;

class BroadcastAs implements ShouldBroadcastToAnalytics
{
    public function broadcastAnalyticsActionAs()
    {
        return 'CustomEventAction';
    }
}
