<?php

namespace App\Domain\Exception;

use Exception;

class InvalidDiscountValueException extends Exception
{
    public function __construct(int $value = 0)
    {
        $message = sprintf('Invalid discount value: %d', $value);
        parent::__construct($message, 0, null);
    }
}