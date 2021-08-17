<?php

namespace ProtoneMedia\AnalyticsEventTracking\Tests;

use ProtoneMedia\AnalyticsEventTracking\Http\ClientIdSession;

class ClientIdSessionTest extends TestCase
{
    /** @test */
    public function it_stores_the_client_id_by_the_given_key()
    {
        config(['analytics-event-tracking.google.client_id_session_key' => 'someKey']);

        $clientIdSession = new ClientIdSession(session()->driver(), 'someKey');
        $clientIdSession->update('1337');

        $this->assertEquals('1337', session('someKey'));
        $this->assertEquals('1337', $clientIdSession->get());
        $this->assertEquals('1337', app('analytics-event-tracking.google.client-id'));
    }

    /** @test */
    public function it_generates_a_random_key_if_none_is_set()
    {
        $clientIdSession = new ClientIdSession(session()->driver(), 'someKey');

        $this->assertNotNull($clientId = $clientIdSession->get());
        $this->assertEquals($clientId, $clientIdSession->get());
    }
}
