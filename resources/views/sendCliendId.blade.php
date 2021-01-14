if (typeof ga !== 'undefined') {
    ga(function () {
        var clientId = ga.getAll()[0].get('clientId');

        if (clientId != @json(app('analytics-event-tracking.client-id'))) {
            window.axios.post('/gaid', { id: clientId });
        }
    });
} else {
    gtag('get', @json(config('analytics-event-tracking.tracking_id')), 'client_id', function (clientId) {
        if (clientId != @json(app('analytics-event-tracking.client-id'))) {
            window.axios.post('/gaid', { id: clientId });
        }
    });
}