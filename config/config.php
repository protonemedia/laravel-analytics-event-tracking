<?php

return [
    /**
     * Your Google Analytics Tracking ID.
     * https://support.google.com/analytics/answer/1008080
     */
    'tracking_id' => env('GOOGLE_ANALYTICS_TRACKING_ID'),

    /**
     * Use SSL to make calls to Google Analytics.
     */
    'use_ssl' => true,

    /**
     * Anonymize IP when making calls to Google Analytics.
     */
    'anonymize_ip' => true,

    /**
     * Send the ID of the authenticated user to Google Analytics.
     */
    'send_user_id' => true,

    /*
     * This queue will be used to perform the API calls to Google Analytics.
     * Leave empty to use the default queue.
     */
    'queue_name' => '',

    /**
     * The session key to store the Client ID.
     */
    'client_id_session_key' => 'analytics-event-tracker-client-id',

    /**
     * HTTP URI to store the Client ID.
     */
    'http_uri' => '/gaid',
];
