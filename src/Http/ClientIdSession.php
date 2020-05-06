<?php

namespace ProtoneMedia\AnalyticsEventTracking\Http;

use Illuminate\Session\Store;
use Illuminate\Support\Str;

class ClientIdSession implements ClientIdRepostory
{
    private Store $session;
    private string $key;

    public function __construct(Store $session, string $key)
    {
        $this->session = $session;
        $this->key     = $key;
    }

    /**
     * Stores the Client ID in the session.
     */
    public function update(string $clientId): void
    {
        $this->session->put($this->key, $clientId);
    }

    /**
     * Gets the Client ID from the session or generates one.
     */
    public function get(): ?string
    {
        return $this->session->get($this->key, fn () => $this->generateId());
    }

    /**
     * Generates a UUID and stores it in the session.
     */
    private function generateId(): string
    {
        return tap(Str::uuid(), fn ($id) => $this->update($id));
    }
}
