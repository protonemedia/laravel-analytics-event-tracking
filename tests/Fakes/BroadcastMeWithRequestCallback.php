<?php

namespace ProtoneMedia\AnalyticsEventTracking\Tests\Fakes;

use Illuminate\Http\Client\PendingRequest;
use ProtoneMedia\AnalyticsEventTracking\ShouldBroadcastToAnalytics;

class BroadcastMeWithRequestCallback implements ShouldBroadcastToAnalytics
{
    public function withAnalytics(PendingRequest $request)
    {
        $request->withUserAgent('ProtoneMedia');
    }
}
