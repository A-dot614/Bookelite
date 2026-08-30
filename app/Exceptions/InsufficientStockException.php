<?php

namespace App\Exceptions;

class InsufficientStockException extends CheckoutException
{
    public function __construct(string $title, int $requested, int $available)
    {
        parent::__construct(
            sprintf('"%s" only has %d unit(s) left in stock.', $title, $available)
        );
    }
}