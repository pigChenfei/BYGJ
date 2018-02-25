<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'carrier',
        'passwords' => 'carrier_user',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'carrier' => [
            'driver' => 'session',
            'provider' => 'carrier_user',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admin_user',
        ],
        'agent' => [
            'driver' => 'session',
            'provider' => 'agent_user',
        ],
        'member' => [
            'driver' => 'session',
            'provider' => 'member_user',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'carrier_user' => [
            'driver' => 'eloquent',
            'model' => \App\Models\CarrierUser::class,
        ],
        'admin_user'   => [
            'driver' => 'eloquent',
            'model' => \App\Models\AdminUser::class,
        ],
        'agent_user' => [
            'driver' => 'eloquent',
            'model'  => \App\Models\CarrierAgentUser::class
        ],
        'member_user' => [
            'driver' => 'eloquent',
            'model'  => \App\Models\Player::class
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'carrier_user' => [
            'provider' => 'carrier_user',
            'table' => 'carrier_password_resets',
            'expire' => 60,
        ],
        'admin_user'   => [
            'provider' => 'admin_user',
            'table' => 'admin_password_resets',
            'expire' => 60,
        ],
        'agent_user'   => [
            'provider' => 'agent_user',
            'table' => 'agent_password_resets',
            'expire' => 60,
        ],
        'member_user' => [
            'provider' => 'member_user',
            'table' => 'member_password_resets',
            'expire' => 60,
        ]
    ],

];
