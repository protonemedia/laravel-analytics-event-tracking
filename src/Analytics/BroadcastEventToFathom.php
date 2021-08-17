<?php

namespace ProtoneMedia\AnalyticsEventTracking\Analytics;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use TheIconic\Tracking\GoogleAnalytics\Analytics;

class BroadcastEventToFathom implements EventBroadcaster
{
    private PendingRequest $request;

    public function __construct()
    {
        $this->request = Http::asJson()
            ->withToken(config('analytics-event-tracking.fathom.api_token'))
            ->baseUrl('https://api.usefathom.com/v1');
    }

    /**
     * Call the callback with the Request instance.
     */
    public function withAnalytics(callable $callback): self
    {
        $callback($this->request);

        return $this;
    }

    /**
     * Sets the name of the event, calls the 'withAnalytics' method
     * on the events if it exists and then sends the event
     * to Google Analytics.
     */
    public function handle($event): void
    {
        $eventName = method_exists($event, 'broadcastAnalyticsActionAs')
            ? $event->broadcastAnalyticsActionAs($this->request)
            : Str::slug(Str::kebab(class_basename($event)), '-');

        if (method_exists($event, 'withAnalytics')) {
            $event->withAnalytics($this->request);
        }

        $siteId = config('analytics-event-tracking.fathom.site_id');

        $this->request->post("sites/{$siteId}/events", [
            'name' => $eventName,
        ]);
    }
}
