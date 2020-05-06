<?php

namespace ProtoneMedia\AnalyticsEventTracking;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use ProtoneMedia\AnalyticsEventTracking\Analytics\BroadcastEvent;
use ProtoneMedia\AnalyticsEventTracking\Analytics\EventBroadcaster;
use ProtoneMedia\AnalyticsEventTracking\Events\ShouldBroadcastToAnalytics;
use ProtoneMedia\AnalyticsEventTracking\Http\ClientIdRepostory;
use ProtoneMedia\AnalyticsEventTracking\Http\ClientIdSession;
use ProtoneMedia\AnalyticsEventTracking\Http\StoreClientIdInSession;
use ProtoneMedia\AnalyticsEventTracking\Listeners\DispatchAnalyticsJob;
use TheIconic\Tracking\GoogleAnalytics\Analytics;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'analytics-event-tracking');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('analytics-event-tracking.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/analytics-event-tracking'),
            ], 'views');
        }

        Event::listen(ShouldBroadcastToAnalytics::class, DispatchAnalyticsJob::class);

        Blade::directive('sendAnalyticsClientId', function () {
            return "<?php echo view('analytics-event-tracking::sendCliendId'); ?>";
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php',
            'analytics-event-tracking'
        );

        $this->app->singleton(EventBroadcaster::class, BroadcastEvent::class);
        $this->app->singleton(ClientIdRepostory::class, ClientIdSession::class);

        $this->app->bind('analytics-event-tracking-client-id', function () {
            return app(ClientIdSession::class)->get();
        });

        $this->app->singleton(ClientIdSession::class, function () {
            return new ClientIdSession(
                app('session.store'),
                config('analytics-event-tracking.client_id_session_key')
            );
        });

        $this->app->bind(Analytics::class, function () {
            return tap(new Analytics(true), function (Analytics $analytics) {
                if (config('analytics-event-tracking.send_user_id') && Auth::check()) {
                    $analytics->setUserId(Auth::id());
                }

                if (config('analytics-event-tracking.anonymize_ip')) {
                    $analytics->setAnonymizeIp(1);
                }

                $analytics->setTrackingId(
                    config('analytics-event-tracking.tracking_id')
                );
            });
        });

        if ($httpUri = config('analytics-event-tracking.http_uri')) {
            Route::post($httpUri, StoreClientIdInSession::class);
        }
    }
}
