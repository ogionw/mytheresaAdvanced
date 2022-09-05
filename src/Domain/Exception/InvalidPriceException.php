<?php

namespace App\Domain\Exception;

use Exception;
use Throwable;

class InvalidPriceException extends Exception
{
    public function __construct(int $price, string $sku)
    {
        $message = sprintf('Invalid price: %d for product with sku: %s', $price, $sku);
        parent::__construct($message, 0, null);
    }
}