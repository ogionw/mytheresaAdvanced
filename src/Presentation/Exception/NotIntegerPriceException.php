<?php

namespace App\Presentation\Exception;

use Exception;
use Throwable;

class NotIntegerPriceException extends Exception
{
    public function __construct(string $price)
    {
        $message = sprintf('Not integer price: "%s"', $price);
        parent::__construct($message, 0, null);
    }
}