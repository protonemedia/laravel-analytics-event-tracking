<?php

namespace ProtoneMedia\AnalyticsEventTracking\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\AnalyticsEventTracking\Analytics\EventBroadcaster;
use ProtoneMedia\AnalyticsEventTracking\Events\ShouldBroadcastToAnalytics;
use TheIconic\Tracking\GoogleAnalytics\Analytics;

class SendEventToAnalytics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ShouldBroadcastToAnalytics $event;
    public ?string $clientId;

    public function __construct(ShouldBroadcastToAnalytics $event, string $clientId = null)
    {
        $this->event    = $event;
        $this->clientId = $clientId;
    }

    public function handle(EventBroadcaster $broadcaster)
    {
        if ($this->clientId) {
            $broadcaster->withAnalytics(fn (Analytics $analytics) => $analytics->setClientId($this->clientId));
        }

        $broadcaster->handle($this->event);
    }
}
