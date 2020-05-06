ga(function () {
    var clientId = ga.getAll()[0].get('clientId');

    if(clientId != @json(app('analytics-event-tracking-client-id'))) {
        window.axios.post('/gaid', { id: clientId });
    }
}