<?php

namespace ProtoneMedia\AnalyticsEventTracking\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use ProtoneMedia\AnalyticsEventTracking\ServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('analytics-event-tracking.google.tracking_id', 'UA-11111111-11');
    }

    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }
}
