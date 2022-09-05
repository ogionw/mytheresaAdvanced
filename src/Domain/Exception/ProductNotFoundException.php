<?php

namespace App\Domain\Exception;

use Exception;
use Throwable;

class ProductNotFoundException extends Exception
{
    public function __construct(string $sku)
    {
        $message = sprintf('Not found sku: %s', $sku);
        parent::__construct($message, 0, null);
    }
}