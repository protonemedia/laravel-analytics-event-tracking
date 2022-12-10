<?php

namespace ProtoneMedia\AnalyticsEventTracking\Tests;

use ProtoneMedia\AnalyticsEventTracking\ServiceProvider;
use TheIconic\Tracking\GoogleAnalytics\Analytics;

class ServiceProviderTest extends TestCase
{
    /** @test */
    public function it_sets_the_tracking_id_from_the_configuration()
    {
        $this->assertStringContainsString('tid=UA-11111111-11', app(Analytics::class)->getUrl());
    }

    /** @test */
    public function it_can_disable_sending_events()
    {
        config(['analytics-event-tracking.is_enabled' => false]);

        $serviceProvider = new ServiceProvider($this->app);
        $serviceProvider->registerAnalytics();

        $analytics = $this->app->make(Analytics::class);

        // Make 'isDisabled' protected property accessible
        $reflection = new \ReflectionClass($analytics);
        $property   = $reflection->getProperty('isDisabled');
        $property->setAccessible(true);

        $this->assertTrue($property->getValue($analytics));
    }

    /** @test */
    public function it_can_disable_ssl()
    {
        config(['analytics-event-tracking.use_ssl' => true]);

        $this->assertStringContainsString('https://', app(Analytics::class)->getUrl());

        config(['analytics-event-tracking.use_ssl' => false]);

        $this->assertStringContainsString('http://', app(Analytics::class)->getUrl());
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
