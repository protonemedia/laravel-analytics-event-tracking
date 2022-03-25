<?php

namespace ProtoneMedia\AnalyticsEventTracking\Analytics;

use TheIconic\Tracking\GoogleAnalytics\Analytics;

class BroadcastEvent implements EventBroadcaster
{
    private Analytics $analytics;

    public function __construct(Analytics $analytics)
    {
        $this->analytics = $analytics;
    }

    /**
     * Call the callback with the Analytics instance.
     */
    public function withAnalytics(callable $callback): self
    {
        $callback($this->analytics);

        return $this;
    }

    /**
     * Sets the name of the EventAction, calls the 'withAnalytics' method
     * on the events if it exists and then sends the event
     * to Google Analytics.
     */
    public function handle($event): void
    {

        if (config('analytics-event-tracking.is_disabled')) {
            return;
        }

        $eventAction = method_exists($event, 'broadcastAnalyticsActionAs')
            ? $event->broadcastAnalyticsActionAs($this->analytics)
            : class_basename($event);

        $this->analytics->setEventAction($eventAction);

        if (method_exists($event, 'withAnalytics')) {
            $event->withAnalytics($this->analytics);
        }

        $this->analytics->sendEvent();
    }
}
