<?php

namespace ProtoneMedia\AnalyticsEventTracking\Tests;

class StoreClientIdInSessionTest extends TestCase
{
    /** @test */
    public function it_can_post_the_client_id()
    {
        config(['app.key' => 'base64:xGhskOzI1x0Q0Zl+FHP55ReD33Fp7glHyh+lZyJxOtM=']);

        $this->post('/gaid', ['id' => '1337'])
            ->assertOk()
            ->assertSessionHas('analytics-event-tracker-client-id', '1337');
    }
}
