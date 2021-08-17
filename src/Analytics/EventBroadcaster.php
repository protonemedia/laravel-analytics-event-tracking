<?php

namespace ProtoneMedia\AnalyticsEventTracking\Analytics;

interface EventBroadcaster
{
    public function handle($event);
}
