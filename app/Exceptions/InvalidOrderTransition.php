<?php

namespace App\Exceptions;

use RuntimeException;

class InvalidOrderTransition extends RuntimeException
{
    public function __construct(string $current, string $requested)
    {
        parent::__construct(
            sprintf('Cannot transition an order from "%s" to "%s".', $current, $requested)
        );
    }
}