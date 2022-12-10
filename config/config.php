<?php

return [
    /**
     * Your GA Tracking ID.
     * https://support.google.com/analytics/answer/1008080
     */
    'tracking_id' => env('GOOGLE_ANALYTICS_TRACKING_ID'),

    /**
     * Enable sending events to GA.
     */
    'is_enabled' => true,

    /**
     * Use SSL to make calls to GA.
     */
    'use_ssl' => true,

    /**
     * Anonymizes the last digits of the user's IP.
     */
    'anonymize_ip' => true,

    /**
     * Send the ID of the authenticated user to GA.
     */
    'send_user_id' => false,

    /*
     * This queue will be used to perform the API calls to GA.
     * Leave empty to use the default queue.
     */
    'queue_name' => '',

    /**
     * The session key to store the Client ID.
     */
    'client_id_session_key' => 'analytics-event-tracker-client-id',

    /**
     * HTTP URI to post the Client ID to (from the Blade Directive).
     */
    'http_uri' => '/gaid',
];
