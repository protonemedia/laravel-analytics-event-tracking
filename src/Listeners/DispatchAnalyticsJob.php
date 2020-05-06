<?php

namespace ProtoneMedia\AnalyticsEventTracking\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use ProtoneMedia\AnalyticsEventTracking\Events\ShouldBroadcastToAnalytics;
use ProtoneMedia\AnalyticsEventTracking\Http\ClientIdRepostory;
use ProtoneMedia\AnalyticsEventTracking\Jobs\SendEventToAnalytics;

class DispatchAnalyticsJob
{
    use InteractsWithQueue;

    public ClientIdRepostory $clientIdRepository;

    public function __construct(ClientIdRepostory $clientIdRepository)
    {
        $this->clientIdRepository = $clientIdRepository;
    }

    /**
     * Gets the Client ID from the repository and dispatched the event
     */
    public function handle(ShouldBroadcastToAnalytics $event): void
    {
        SendEventToAnalytics::dispatch($event, $this->clientIdRepository->get());
    }
}
