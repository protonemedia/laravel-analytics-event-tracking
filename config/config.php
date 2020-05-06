<?php

return [
    'tracking_id' => env('GOOGLE_ANALYTICS_TRACKING_ID'),

    'client_id_session_key' => 'analytics-event-tracker-client-id',

    // set to null to disable
    'http_uri' => 'gaid',

    'use_ssl' => true,

    'anonymize_ip' => true,

    'send_user_id' => true,
];
