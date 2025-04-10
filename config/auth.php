<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paramètres par défaut d'authentification
    |--------------------------------------------------------------------------
    |
    | Définit le garde ("guard") et le courtier de réinitialisation de mot de passe
    | par défaut pour votre application. Vous pouvez modifier ces valeurs selon
    | vos besoins, mais elles constituent une bonne base pour la plupart des applications.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Gardes d'authentification
    |--------------------------------------------------------------------------
    |
    | Définit tous les gardes d'authentification disponibles pour votre application.
    | La configuration par défaut utilise le stockage de session avec le fournisseur
    | d'utilisateurs Eloquent, ce qui est idéal pour la plupart des applications.
    |
    | Chaque garde a un fournisseur d'utilisateurs qui définit comment les utilisateurs
    | sont récupérés depuis votre base de données ou autre système de stockage.
    |
    | Supporté : "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
            'hash' => false, // Important pour le contrôle des mots de passe
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Fournisseurs d'utilisateurs
    |--------------------------------------------------------------------------
    |
    | Configure comment les utilisateurs sont récupérés depuis votre système de stockage.
    | Eloquent est utilisé par défaut, mais vous pouvez aussi utiliser une table de base
    | de données directement si nécessaire.
    |
    | Supporté : "database", "eloquent"
    |
    */

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

        // Alternative avec table directe (décommenter si nécessaire)
        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Réinitialisation des mots de passe
    |--------------------------------------------------------------------------
    |
    | Configuration du comportement de la réinitialisation des mots de passe :
    | - Table de stockage des jetons
    | - Fournisseur d'utilisateurs à utiliser
    | - Durée de validité des jetons (en minutes)
    | - Délai entre les demandes (en secondes)
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60, // 1 heure avant expiration
            'throttle' => 120, // 2 minutes entre les demandes
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Délai de confirmation du mot de passe
    |--------------------------------------------------------------------------
    |
    | Durée en secondes avant qu'une confirmation de mot de passe ne soit
    | considérée comme expirée. Par défaut : 3 heures (10800 secondes).
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

    /*
    |--------------------------------------------------------------------------
    | Messages d'erreur en français
    |--------------------------------------------------------------------------
    */

    'errors' => [
        'failed' => 'Identifiants incorrects. Veuillez réessayer.',
        'password' => 'Le mot de passe est incorrect.',
        'throttle' => 'Trop de tentatives de connexion. Veuillez réessayer dans :seconds secondes.',
        'inactive' => 'Votre compte est désactivé.',
        'unauthorized' => 'Accès non autorisé.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Paramètres de sécurité avancés
    |--------------------------------------------------------------------------
    */

    'security' => [
        'password_history' => env('AUTH_PASSWORD_HISTORY', 5), // Nombre d'anciens mots de passe à mémoriser
        'max_attempts' => env('AUTH_MAX_ATTEMPTS', 5), // Tentatives de connexion avant verrouillage
        'lockout_time' => env('AUTH_LOCKOUT_TIME', 300), // 5 minutes de verrouillage
        'force_logout' => env('AUTH_FORCE_LOGOUT', true), // Déconnexion forcée après changement de mot de passe
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration des sessions
    |--------------------------------------------------------------------------
    */

    'session' => [
        'single_device' => env('AUTH_SINGLE_DEVICE', false), // Connexion unique simultanée
        'inactivity_timeout' => env('AUTH_INACTIVITY_TIMEOUT', 1800), // 30 minutes d'inactivité
    ],
];