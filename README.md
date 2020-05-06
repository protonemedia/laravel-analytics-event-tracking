# Laravel Analytics Event Tracking
https://twitter.com/pascalbaljet/status/1257926601339277312

[![Latest Version on Packagist](https://img.shields.io/packagist/v/protonemedia/laravel-analytics-event-tracking.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-analytics-event-tracking)
[![Build Status](https://img.shields.io/travis/pascalbaljetmedia/laravel-analytics-event-tracking/master.svg?style=flat-square)](https://travis-ci.org/pascalbaljetmedia/laravel-analytics-event-tracking)
[![Quality Score](https://img.shields.io/scrutinizer/g/pascalbaljetmedia/laravel-analytics-event-tracking.svg?style=flat-square)](https://scrutinizer-ci.com/g/pascalbaljetmedia/laravel-analytics-event-tracking)
[![Total Downloads](https://img.shields.io/packagist/dt/protonemedia/laravel-analytics-event-tracking.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-analytics-event-tracking)

Laravel package to easily send events to Google Analytics

## Features
* Use [Laravel Events](https://laravel.com/docs/7.x/events) to track events with Google Analytics
* All API calls are queued.
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

Set your [Google Analytics Tracking ID](https://support.google.com/analytics/answer/1008080) in the `.env` file or in the `config/analytics-event-tracking.php` file.

```bash
GOOGLE_ANALYTICS_TRACKING_ID=UA-01234567-89
```

## Broadcast Events to Google Analytics

Add the `ShouldBroadcastToAnalytics` interface to your event and you're ready! You don't have to manually bind any listeners.

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

With the `withAnalytics` method you can interact with the [underlying package](https://github.com/theiconic/php-ga-measurement-protocol) to set additional parameters. Take a look at the `TheIconic\Tracking\GoogleAnalytics\Analytics` class to see the available methods.

With the `broadcastAnalyticsActionAs` method you can customize the name of the [Event Action](https://developers.google.com/analytics/devguides/collection/analyticsjs/field-reference#eventAction). By default we use the class name with the class's namespace removed. This method gives you access to the underlying `Analytics` class as well.

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
        $analytics->setEventValue($this->order->sum_in_cents * 100);
    }

    public function broadcastAnalyticsActionAs(Analytics $analytics)
    {
        return 'CustomEventAction';
    }
}
```

## Additional configuration

You can configure some additional settings in the `config/analytics-event-tracking.php` file:

* `use_ssl`: Use SSL to make calls to GA
* `anonymize_ip`: Anonymize IP when making calls to GA
* `send_user_id`: Send the ID of the authenticated user to GA
* `queue_name`: Specify a queue to make the calls to GA
* `client_id_session_key`: The session key to store the Client ID
* `http_uri`: HTTP URI to post the Client ID to (from the Blade Directive)

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
