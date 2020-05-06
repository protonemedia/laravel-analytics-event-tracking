<?php

namespace ProtoneMedia\AnalyticsEventTracking\Tests;

class StoreClientIdInSessionTest extends TestCase
{
    /** @test */
    public function it_can_post_the_client_id()
    {
        $this->post('/gaid', ['id' => '1337'])
            ->assertOk()
            ->assertSessionHas('analytics-event-tracker-client-id', '1337');
    }
}
