<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Storefront business configuration
    |--------------------------------------------------------------------------
    |
    | These values drive order totals. They are intentionally configuration
    | driven (not hard-coded in views) so real business rules can be changed
    | without touching templates.
    |
    */

    'currency' => env('ECOMMERCE_CURRENCY', 'USD'),
    'currency_symbol' => env('ECOMMERCE_CURRENCY_SYMBOL', '$'),

    // Tax is a flat percentage of the subtotal (0.0 = no tax).
    'tax_rate' => (float) env('ECOMMERCE_TAX_RATE', 0),

    // Flat-rate shipping applied to orders below the free-shipping threshold.
    'shipping_flat_rate' => (float) env('ECOMMERCE_SHIPPING_FLAT_RATE', 0),
    'free_shipping_threshold' => (float) env('ECOMMERCE_FREE_SHIPPING_THRESHOLD', 0),
    'free_shipping_label' => env('ECOMMERCE_FREE_SHIPPING_LABEL', 'Free Shipping'),

    'countries' => array_values(array_filter(array_map('trim', explode(',', env(
        'ECOMMERCE_COUNTRIES',
        'United States,United Kingdom,France,Germany,Canada,Australia,Japan,Other International'
    ))))),

    // When a customer is required to have purchased a product before reviewing it.
    'reviews_require_purchase' => (bool) env('ECOMMERCE_REVIEWS_REQUIRE_PURCHASE', true),

    // Pagination defaults
    'per_page' => (int) env('ECOMMERCE_PER_PAGE', 12),

];