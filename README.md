# Laravel Analytics Event Tracking
https://twitter.com/pascalbaljet/status/1257926601339277312

[![Latest Version on Packagist](https://img.shields.io/packagist/v/protonemedia/laravel-analytics-event-tracking.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-analytics-event-tracking)
[![Build Status](https://img.shields.io/travis/pascalbaljetmedia/laravel-analytics-event-tracking/master.svg?style=flat-square)](https://travis-ci.org/pascalbaljetmedia/laravel-analytics-event-tracking)
[![Quality Score](https://img.shields.io/scrutinizer/g/pascalbaljetmedia/laravel-analytics-event-tracking.svg?style=flat-square)](https://scrutinizer-ci.com/g/pascalbaljetmedia/laravel-analytics-event-tracking)
[![Total Downloads](https://img.shields.io/packagist/dt/protonemedia/laravel-analytics-event-tracking.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-analytics-event-tracking)

Laravel package to easily send events to Google Analytics

## Features
* Compatible with Laravel 6.0 and 7.0.
* PHP 7.4 required.

## Installation

You can install the package via composer:

```bash
composer require protonemedia/laravel-analytics-event-tracking
```

## Configuration

Publish the config and view files:

```bash
php artisan vendor:publish --provider="ProtoneMedia\AnalyticsEventTracking\ServiceProvider"
```

Set your [Google Analytics tracking ID](https://support.google.com/analytics/answer/1008080) in your `.env` file or in the `config/analytics-event-tracking.php` file.

```bash
GOOGLE_ANALYTICS_TRACKING_ID=UA-01234567-89
```

## Broadcast Events to Google Analytics

Just add the `ShouldBroadcastToAnalytics` interface to your event and you're ready!

``` php
<?php

namespace App\Events;

use App\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\AnalyticsEventTracking\Events\ShouldBroadcastToAnalytics;

class OrderWasPaid implements ShouldBroadcastToAnalytics
{
    use Dispatchable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
```

## Customize the broadcast

There are two additional methods that lets you customize the call to Google Analytics.

With the `withAnalytics` you can interact with the [underlying package](https://github.com/theiconic/php-ga-measurement-protocol) to set additional parameters. Take a look at the `TheIconic\Tracking\GoogleAnalytics\Analytics` class to see the available methods.

With the `broadcastAnalyticsActionAs` you can customize the name of the [Event Action](https://developers.google.com/analytics/devguides/collection/analyticsjs/field-reference#eventAction). By default we use the class name with the class's namespace removed. This method gives you access to the underlying `Analytics` class as well.

``` php
<?php

namespace App\Events;

use App\Order;
use TheIconic\Tracking\GoogleAnalytics\Analytics;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\AnalyticsEventTracking\Events\ShouldBroadcastToAnalytics;

class OrderWasPaid implements ShouldBroadcastToAnalytics
{
    use Dispatchable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function withAnalytics(Analytics $analytics)
    {
        $analytics->setEventValue(100);
    }

    public function broadcastAnalyticsActionAs(Analytics $analytics)
    {
        return 'CustomEventAction';
    }
}
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email pascal@protone.media instead of using the issue tracker.

## Credits

- [Pascal Baljet](https://github.com/pascalbaljetmedia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
