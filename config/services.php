<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'rates_service' => [
        'base_url' => env('RATES_SERVICE_URL', 'http://127.0.0.1:8091'),
        'token' => env('RATES_SERVICE_TOKEN'),
    ],

    'frankfurter' => [
        'base_url' => env('FRANKFURTER_BASE_URL', 'https://api.frankfurter.dev/v2'),
    ],

    'loan_service' => [
        'url' => env('LOAN_SERVICE_URL', 'http://127.0.0.1:8092'),
        'token' => env('LOAN_SERVICE_TOKEN'),
    ],

];
