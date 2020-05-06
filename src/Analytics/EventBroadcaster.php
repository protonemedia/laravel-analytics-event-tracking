<?php

namespace ProtoneMedia\AnalyticsEventTracking\Analytics;

interface EventBroadcaster
{
    public function handle($event);

    public function withAnalytics(callable $callback): self;
}
