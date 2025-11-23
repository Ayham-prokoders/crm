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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'telegram-bot-api' => [
        'token' => env('TELEGRAM_BOT_TOKEN'),
    ],

    'telegram' => [
        'token' => env('TELEGRAM_BOT_TOKEN'),
        'chat_id' => env('TELEGRAM_CHAT_ID'),
    ],
    /*
    |  the websites urls
    */
    'sites' => [
        'lpc_en' => [
            'project' => 'LPC English',
            'url' => env('LPC_EN_URL'),
            'shared_secret' => env('LPC_EN_SECRET','xrcUD2Lx9NWu3RVndwW1ozPZc20c5IvQDQQuzujIdZOyfYDuiz1OVfryOUL1y20O'),
        ],
        'lpc_ar' => [
            'project' => 'LPC Arabic',
            'url' => env('LPC_AR_URL'),
            'shared_secret' => env('LPC_AR_SECRET','AnoIm5ntHpv3OaFSe4Y4DtNxFMW9ElGfBajJpokMSo9HE5GWyeXeF4nJTe4pt2Le'),
        ],
        //to be like project_source
        'L1' => [
            'project' => 'LPC English',
            'url' => env('LPC_EN_URL'),
            'shared_secret' => env('LPC_EN_SECRET','xrcUD2Lx9NWu3RVndwW1ozPZc20c5IvQDQQuzujIdZOyfYDuiz1OVfryOUL1y20O'),
        ],
        'L0' => [
            'project' => 'LPC Arabic',
            'url' => env('LPC_AR_URL'),
            'shared_secret' => env('LPC_AR_SECRET','AnoIm5ntHpv3OaFSe4Y4DtNxFMW9ElGfBajJpokMSo9HE5GWyeXeF4nJTe4pt2Le'),
        ],
        'R1' => [
            'project' => 'Regent English',
            'url' => env('REGENT_URL_EN'),
            'shared_secret' => env('REGENT_EN_SECRET','j1mpXDWbFKCLPlP8mrL7S9rmqXPLJ3j5vVZWL5TgaWsTfYW5zmXYAWlDgNvERTvQ'),
        ],
    ],

    'lms' => [
        'broadcast_token' => env('LMS_BROADCAST_TOKEN'),
    ],
    'survey' => [
        'hmac_secret' => env('SURVEY_HMAC_SECRET', '5r2dchaYiesxRWCF45WpPdOkPRGpfKulIAktbDMhcvhYNr6s0FlUnBfsdJp6f62t'),
    ],
    'webhook' => [
        'secret' => env('WEBHOOK_SECRET'),
    ],

    'twilio' => [
        'sid'   => env('TWILIO_SID'),
        'token' => env('TWILIO_AUTH_TOKEN'),
        'from'  => env('TWILIO_PHONE_NUMBER'),
    ],

];

