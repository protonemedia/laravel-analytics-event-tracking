<?php

namespace ProtoneMedia\AnalyticsEventTracking\Http;

interface ClientIdRepostory
{
    public function update(string $clientId): void;

    public function get(): ?string;
}
