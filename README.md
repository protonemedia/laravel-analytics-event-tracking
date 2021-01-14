# Laravel Analytics Event Tracking
https://twitter.com/pascalbaljet/status/1257926601339277312

[![Latest Version on Packagist](https://img.shields.io/packagist/v/protonemedia/laravel-analytics-event-tracking.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-analytics-event-tracking)
![run-tests](https://github.com/protonemedia/laravel-analytics-event-tracking/workflows/run-tests/badge.svg)
[![Quality Score](https://img.shields.io/scrutinizer/g/protonemedia/laravel-analytics-event-tracking.svg?style=flat-square)](https://scrutinizer-ci.com/g/protonemedia/laravel-analytics-event-tracking)
[![Total Downloads](https://img.shields.io/packagist/dt/protonemedia/laravel-analytics-event-tracking.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-analytics-event-tracking)
[![Buy us a tree](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-lightgreen)](https://plant.treeware.earth/protonemedia/laravel-analytics-event-tracking)

Laravel package to easily send events to [Google Analytics](https://analytics.google.com/)

## Features
* Use [Laravel Events](https://laravel.com/docs/7.x/events) to track events with GA.
* [Blade Directive](https://laravel.com/docs/7.x/blade#introduction) to easily store the Client ID.
* Full access to the [underlying library](https://github.com/theiconic/php-ga-measurement-protocol).
* API calls to GA are queued.
* Easy to configure.
* Compatible with Laravel 6.0 and higher.
* PHP 7.4 or higher required.

## Support

We proudly support the community by developing Laravel packages and giving them away for free. Keeping track of issues and pull requests takes time, but we're happy to help! If this package saves you time or if you're relying on it professionally, please consider [supporting the maintenance and development](https://github.com/sponsors/pascalbaljet).

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

This package supports [Google Analytics 4](https://blog.google/products/marketingplatform/analytics/new_google_analytics/) as of version 1.2.1. Please republish the view file if you're upgrading to a new Google Analytics 4 property.

## Blade Directive

This package comes with a `@sendAnalyticsClientId` directive that sends the [Client ID](https://developers.google.com/analytics/devguides/collection/analyticsjs/field-reference#clientId) from the GA front-end to your Laravel backend and stores it in the session.

It uses the [Axios HTTP library](https://github.com/axios/axios) the make an asynchronous POST request. Axios was choosen because it is provided by default in Laravel in the `resources/js/bootstrap.js` file.

Add the directive somewhere after initializing/configuring GA. The POST request will only be made if the `Client ID` isn't stored yet or when it's refreshed.

```php
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-01234567-89"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-01234567-89', { 'send_page_view': false });
  gtag('event', 'page_view', { 'event_callback': function() {
      @sendAnalyticsClientId
  }});
</script>
```

If you don't use Axios, you have to implement this call by yourself. By default the endpoint is `/gaid` but you can customize it in the configuration file. The request is handled by the `ProtoneMedia\AnalyticsEventTracking\Http\StoreClientIdInSession` class. Make sure to also send the [CSRF token](https://laravel.com/docs/7.x/csrf).

## Broadcast events to Google Analytics

Add the `ShouldBroadcastToAnalytics` interface to your event and you're ready! You don't have to manually bind any listeners.

``` php
<?php

namespace App\Events;

use App\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\AnalyticsEventTracking\ShouldBroadcastToAnalytics;

class OrderWasCreated implements ShouldBroadcastToAnalytics
{
    use Dispatchable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
```

## Handle framework and 3rd-party events

If you want to handle events where you can't add the `ShouldBroadcastToAnalytics` interface, you can manually register them in your `EventServiceProvider` using the `DispatchAnalyticsJob` listener.

```php
<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use ProtoneMedia\AnalyticsEventTracking\Listeners\DispatchAnalyticsJob;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            DispatchAnalyticsJob::class,
        ],
    ];
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
use ProtoneMedia\AnalyticsEventTracking\ShouldBroadcastToAnalytics;

class OrderWasCreated implements ShouldBroadcastToAnalytics
{
    use Dispatchable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function withAnalytics(Analytics $analytics)
    {
        $analytics->setEventValue($this->order->sum_in_cents / 100);
    }

    public function broadcastAnalyticsActionAs(Analytics $analytics)
    {
        return 'CustomEventAction';
    }
}
```

## Handling the Client ID outside a HTTP Request

You might want to track an event that occurs outside of a HTTP Request, for example in a queued job or while handling a 3rd-party callback/webhook. Let's continue with the `Order` example. When the `Order` is created, you could save the `Client ID` in the database.

```php
<?php

namespace App\Http\Controllers;

use App\Order;
use App\Http\Requests\CreateOrderRequest;
use ProtoneMedia\AnalyticsEventTracking\Http\ClientIdRepository;

class CreateOrderController
{
    public function __invoke(CreateOrderRequest $request, ClientIdRepository $clientId)
    {
        $attributes = $request->validated();

        $attributes['google_analytics_client_id'] = $clientId->get();

        return Order::create($attributes);
    }
}
```

When you receive a webhook from your payment provider and you dispatch an `OrderWasPaid` event, you can use the `withAnalytics` method in your event to reuse the `google_analytics_client_id`:

``` php
<?php

namespace App\Events;

use App\Order;
use TheIconic\Tracking\GoogleAnalytics\Analytics;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\AnalyticsEventTracking\ShouldBroadcastToAnalytics;

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
        $analytics->setClientId($this->order->google_analytics_client_id);
    }
}
```

## Additional configuration

You can configure some additional settings in the `config/analytics-event-tracking.php` file:

* `use_ssl`: Use SSL to make calls to GA
* `anonymize_ip`: Anonymizes the last digits of the user's IP
* `send_user_id`: Send the ID of the authenticated user to GA
* `queue_name`: Specify a queue to perform the calls to GA
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

## Other Laravel packages

* [`Laravel Blade On Demand`](https://github.com/protonemedia/laravel-blade-on-demand): Laravel package to compile Blade templates in memory.
* [`Laravel Cross Eloquent Search`](https://github.com/protonemedia/laravel-cross-eloquent-search): Laravel package to search through multiple Eloquent models.
* [`Laravel Eloquent Scope as Select`](https://github.com/protonemedia/laravel-eloquent-scope-as-select): Stop duplicating your Eloquent query scopes and constraints in PHP. This package lets you re-use your query scopes and constraints by adding them as a subquery.
* [`Laravel Eloquent Where Not`](https://github.com/protonemedia/laravel-eloquent-where-not): This Laravel package allows you to flip/invert an Eloquent scope, or really any query constraint.
* [`Laravel FFMpeg`](https://github.com/protonemedia/laravel-ffmpeg): This package provides an integration with FFmpeg for Laravel. The storage of the files is handled by Laravel's Filesystem.
* [`Laravel Form Components`](https://github.com/protonemedia/laravel-form-components): Blade components to rapidly build forms with Tailwind CSS Custom Forms and Bootstrap 4. Supports validation, model binding, default values, translations, includes default vendor styling and fully customizable!
* [`Laravel Mixins`](https://github.com/protonemedia/laravel-mixins): A collection of Laravel goodies.
* [`Laravel Paddle`](https://github.com/protonemedia/laravel-paddle): Paddle.com API integration for Laravel with support for webhooks/events.
* [`Laravel Verify New Email`](https://github.com/protonemedia/laravel-verify-new-email): This package adds support for verifying new email addresses: when a user updates its email address, it won't replace the old one until the new one is verified.
* [`Laravel WebDAV`](https://github.com/protonemedia/laravel-webdav): WebDAV driver for Laravel's Filesystem.

### Security

If you discover any security related issues, please email pascal@protone.media instead of using the issue tracker.

## Credits

- [Pascal Baljet](https://github.com/pascalbaljetmedia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Treeware

This package is [Treeware](https://treeware.earth). If you use it in production, then we ask that you [**buy the world a tree**](https://plant.treeware.earth/pascalbaljetmedia/laravel-analytics-event-tracking) to thank us for our work. By contributing to the Treeware forest you’ll be creating employment for local families and restoring wildlife habitats.

