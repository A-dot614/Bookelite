<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Payment provider configuration
    |--------------------------------------------------------------------------
    |
    | driver = 'manual'   : No real gateway. Only offline methods (bank
    |                      transfer / cash on delivery) are accepted and
    |                      orders are created in a payment_pending state.
    |                      Card/PayPal are rejected until a provider is
    |                      configured.
    | driver = 'stripe'   : Real Stripe PaymentIntents via the payment
    |                      abstraction (requires STRIPE_SECRET_KEY).
    |
    */

    'driver' => env('PAYMENT_DRIVER', 'manual'),

    'instructions' => env('PAYMENT_MANUAL_INSTRUCTIONS',
        'Your order has been registered. Once payment is confirmed your order will be prepared for dispatch.'),

    'providers' => [
        'stripe' => [
            'secret_key' => env('STRIPE_SECRET_KEY'),
            'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
            'currency' => env('STRIPE_CURRENCY', env('ECOMMERCE_CURRENCY', 'USD')),
        ],
    ],

];