<?php
return [

    /*
    |--------------------------------------------------------------------------
    | oAuth Config
    |--------------------------------------------------------------------------
    */

    /**
     * Storage
     */
    'storage' => 'Session',

    /**
     * Consumers
     */
    'consumers' => [

        /**
         * Yahoo
         */
        'Yahoo' => [
            'client_id'     => env('YAHOO_CONSUMER_ID'),
            'client_secret' => env('YAHOO_CONSUMER_SECRET'),
        ],

    ]

];
