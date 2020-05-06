<?php

namespace ProtoneMedia\AnalyticsEventTracking\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\AnalyticsEventTracking\Analytics\EventBroadcaster;
use TheIconic\Tracking\GoogleAnalytics\Analytics;

class SendEventToAnalytics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $event;
    public ?string $clientId;
    public ?string $userId;

    public function __construct($event, string $clientId = null, string $userId = null)
    {
        $this->event    = $event;
        $this->clientId = $clientId;
        $this->userId   = $userId;
    }

    public function handle(EventBroadcaster $broadcaster)
    {
        if ($this->clientId) {
            $broadcaster->withAnalytics(fn (Analytics $analytics) => $analytics->setClientId($this->clientId));
        }

        if ($this->userId) {
            $broadcaster->withAnalytics(fn (Analytics $analytics) => $analytics->setUserId($this->userId));
        }

        $broadcaster->handle($this->event);
    }
}
