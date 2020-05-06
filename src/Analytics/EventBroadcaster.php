<?php

namespace ProtoneMedia\AnalyticsEventTracking\Analytics;

use ProtoneMedia\AnalyticsEventTracking\Events\ShouldBroadcastToAnalytics;

interface EventBroadcaster
{
    public function handle(ShouldBroadcastToAnalytics $event);

    public function withAnalytics(callable $callback): self;
}
