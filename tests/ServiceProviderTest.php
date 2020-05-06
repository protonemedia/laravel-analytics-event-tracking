<?php

namespace ProtoneMedia\AnalyticsEventTracking\Tests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use TheIconic\Tracking\GoogleAnalytics\Analytics;

class ServiceProviderTest extends TestCase
{
    /** @test */
    public function it_sets_the_tracking_id_from_the_configuration()
    {
        $this->assertStringContainsString('tid=UA-11111111-11', app(Analytics::class)->getUrl());
    }

    private function authenticateUser()
    {
        Auth::login(
            User::unguarded(fn () => new User(['id' => 1337]))
        );
    }

    /** @test */
    public function it_sets_the_user_id_if_authenticated_and_configuration_setting_is_enabled()
    {
        config(['analytics-event-tracking.send_user_id' => true]);

        $this->authenticateUser();

        $this->assertStringContainsString('uid=1337', app(Analytics::class)->getUrl());
    }

    /** @test */
    public function it_doesnt_set_the_user_id_if_authenticated_and_configuration_setting_is_disabled()
    {
        config(['analytics-event-tracking.send_user_id' => false]);

        $this->authenticateUser();

        $this->assertStringNotContainsString('uid=1337', app(Analytics::class)->getUrl());
    }

    /** @test */
    public function it_doesnt_set_the_user_id_if_not_authenticated()
    {
        config(['analytics-event-tracking.send_user_id' => true]);

        $this->assertStringNotContainsString('uid=', app(Analytics::class)->getUrl());
    }

    /** @test */
    public function it_sets_the_anonymize_ip_based_on_the_config()
    {
        config(['analytics-event-tracking.anonymize_ip' => true]);
        $this->assertStringContainsString('aip=1', app(Analytics::class)->getUrl());

        config(['analytics-event-tracking.anonymize_ip' => false]);
        $this->assertStringNotContainsString('aip=1', app(Analytics::class)->getUrl());
    }
}
