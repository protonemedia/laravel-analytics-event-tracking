<?php

return [
    /**
     * 'fathom', 'google'
     */
    'provider' => 'google',

    /*
     * This queue will be used to perform the API calls to GA.
     * Leave empty to use the default queue.
     */
    'queue_name' => '',

    /**
     * Fathom settings.
     */
    'fathom' => [
        /**
         * Your Fathom Site ID
         * https://app.usefathom.com/#/settings/sites
         */
        'site_id' => env('FATHOM_SITE_ID'),

        /**
         * Your Fathom API token
         * https://app.usefathom.com/#/settings/api
         */
        'api_token' => env('FATHOM_API_TOKEN'),
    ],

    /**
     * Google Analytics settings.
     */
    'google' => [
        /**
        * Your GA Tracking ID.
        * https://support.google.com/analytics/answer/1008080
        */
        'tracking_id' => env('GOOGLE_ANALYTICS_TRACKING_ID'),

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

        /**
        * The session key to store the Client ID.
        */
        'client_id_session_key' => 'analytics-event-tracker-client-id',

        /**
        * HTTP URI to post the Client ID to (from the Blade Directive).
        */
        'http_uri' => '/gaid',
    ],
];
