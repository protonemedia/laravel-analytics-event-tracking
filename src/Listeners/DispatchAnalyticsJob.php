<?php

namespace ProtoneMedia\AnalyticsEventTracking\Listeners;

use Illuminate\Queue\InteractsWithQueue;
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
    public function handle($event): void
    {
        $job = new SendEventToAnalytics($event, $this->clientIdRepository->get());

        if ($queueName = config('analytics-event-tracking.queue_name')) {
            $job->onQueue($queueName);
        }

        dispatch($job);
    }
}
