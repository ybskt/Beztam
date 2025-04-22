<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
            'hash' => false,
        ],
        
        // Add the admin guard
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
            'hash' => false,
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
            'password_validation' => [
                'min_length' => 8,
                'require_mixed_case' => true,
                'require_numbers' => true,
                'require_symbols' => true,
            ],
        ],
        
        // Add the admin provider
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
            'password_validation' => [
                'min_length' => 12, // Stronger password for admin
                'require_mixed_case' => true,
                'require_numbers' => true,
                'require_symbols' => true,
            ],
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 120,
        ],
        
        // Optionally add password reset for admin (though you might not need it)
        'admins' => [
            'provider' => 'admins',
            'table' => 'admin_password_reset_tokens',
            'expire' => 30, // Shorter expiration for admin tokens
            'throttle' => 300, // Longer throttle period for admin
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

    'errors' => [
        'failed' => 'Identifiants incorrects. Veuillez réessayer.',
        'password' => 'Le mot de passe est incorrect.',
        'throttle' => 'Trop de tentatives de connexion. Veuillez réessayer dans :seconds secondes.',
        'inactive' => 'Votre compte est désactivé.',
        'unauthorized' => 'Accès non autorisé.',
    ],

    'security' => [
        'password_history' => env('AUTH_PASSWORD_HISTORY', 5),
        'max_attempts' => env('AUTH_MAX_ATTEMPTS', 5),
        'lockout_time' => env('AUTH_LOCKOUT_TIME', 300),
        'force_logout' => env('AUTH_FORCE_LOGOUT', true),
        
        // Add stricter security for admin
        'admin_security' => [
            'max_attempts' => 3, // Fewer attempts allowed for admin
            'lockout_time' => 900, // 15 minutes lockout for admin
        ],
    ],

    'session' => [
        'single_device' => env('AUTH_SINGLE_DEVICE', false),
        'inactivity_timeout' => env('AUTH_INACTIVITY_TIMEOUT', 1800),
        
        // Stricter session settings for admin
        'admin_session' => [
            'single_device' => true, // Admin can only be logged in one device at a time
            'inactivity_timeout' => 900, // 15 minutes inactivity timeout for admin
        ],
    ],
];