<?php

return [
    'name' => env('APP_NAME', 'Local Retail Store'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'asset_url' => env('ASSET_URL'),
    'timezone' => 'Asia/Karachi',
    'locale' => env('APP_LOCALE', 'en'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => 'en_US',
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => [
        ...array_filter(explode(',', env('APP_PREVIOUS_KEYS', ''))),
    ],
    'maintenance' => [
        'driver' => 'file',
    ],

    'providers' => [
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        Laravel\Sanctum\SanctumServiceProvider::class,

        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
    ],

    'aliases' => Illuminate\Support\Facades\Facade::defaultAliases()->merge([
        'Str' => Illuminate\Support\Str::class,
        'Arr' => Illuminate\Support\Arr::class,
    ])->toArray(),

    'store' => [
        'phone' => env('STORE_PHONE', '+92 300 0000000'),
        'whatsapp' => env('STORE_WHATSAPP', '+92 300 0000000'),
        'email' => env('STORE_EMAIL', 'support@example.com'),
        'address' => env('STORE_ADDRESS', 'Main Bazaar Road, Your City, Pakistan'),
        'currency' => env('STORE_CURRENCY', 'PKR'),
        'shipping_fee' => (float) env('STORE_SHIPPING_FLAT_FEE', 200),
        'shipping_flat_fee' => (float) env('STORE_SHIPPING_FLAT_FEE', 200),
        'free_shipping_threshold' => (float) env('STORE_FREE_SHIPPING_THRESHOLD', 5000),
        'bank' => [
            'name' => env('STORE_BANK_NAME', ''),
            'account_title' => env('STORE_BANK_ACCOUNT_TITLE', ''),
            'account_number' => env('STORE_BANK_ACCOUNT_NUMBER', ''),
            'iban' => env('STORE_BANK_IBAN', ''),
        ],
    ],
];
