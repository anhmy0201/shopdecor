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
    'google' => [
    'client_id'     => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect'      => env('GOOGLE_REDIRECT_URI'),
    ],

    'payos' => [
    'client_id'    => env('PAYOS_CLIENT_ID'),
    'api_key'      => env('PAYOS_API_KEY'),
    'checksum_key' => env('PAYOS_CHECKSUM_KEY'),
],

'groq' => [
    'api_key' => env('GROQ_API_KEY'),
    'model'   => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
],

'bank' => [
    'so_tai_khoan' => env('BANK_SO_TAI_KHOAN', '1031487289'),
    'chu_tai_khoan' => env('BANK_CHU_TAI_KHOAN', 'TRUONG ANH MY'),
    'ngan_hang'    => env('BANK_NGAN_HANG', 'VCB'),
    'ten_ngan_hang' => env('BANK_TEN_NGAN_HANG', 'Vietcombank'),
    'admin_email'  => env('BANK_ADMIN_EMAIL', env('MAIL_FROM_ADDRESS')),
],
];