<?php

namespace ProtoneMedia\AnalyticsEventTracking\Http;

interface ClientIdRepository
{
    public function update(string $clientId): void;

    public function get(): ?string;
}
