<?php

namespace App\Domain\Exception;

use Exception;
use Throwable;

class DuplicateDiscountException extends Exception
{
    public function __construct(?string $sku, ?string $category)
    {
        $message = sprintf('Duplicate Discount sku: %s category: %s', $sku, $category);
        parent::__construct($message, 0, null);
    }
}