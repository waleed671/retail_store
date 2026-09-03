<?php

return [
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    // Placeholders for future payment gateway integrations (Phase 3+):
    'jazzcash' => [
        'merchant_id' => env('JAZZCASH_MERCHANT_ID'),
        'password' => env('JAZZCASH_PASSWORD'),
        'integrity_salt' => env('JAZZCASH_INTEGRITY_SALT'),
        'sandbox' => env('JAZZCASH_SANDBOX', true),
    ],
    'easypaisa' => [
        'store_id' => env('EASYPAISA_STORE_ID'),
        'account_num' => env('EASYPAISA_ACCOUNT_NUM'),
        'hash_key' => env('EASYPAISA_HASH_KEY'),
        'sandbox' => env('EASYPAISA_SANDBOX', true),
    ],
];
