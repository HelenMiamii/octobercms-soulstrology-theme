<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => '',
        'secret' => '',
    ],

    'mandrill' => [
        'secret' => '',
    ],

    'ses' => [
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
    ],

    'stripe' => [
       'model'   => \OFFLINE\Cashier\Models\User::class,
       'key'     => 'pk_live_h236xL3vJONtbTf9YI28M2X3',
       'secret'  => 'sk_live_jZqMdWclM1oeBYPheQN0O2AH',
       'webhook' => [
           'url'     => '/stripe/webhook',
           'handler' => '\OFFLINE\Cashier\Classes\WebhookController@handleWebhook'
       ]
    ],

];
