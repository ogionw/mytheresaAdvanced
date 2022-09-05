<?php

namespace App\Presentation\Exception;

use Exception;
use Throwable;

class NotIntegerValueException extends Exception
{
    public function __construct(string $value)
    {
        $message = sprintf('Not integer value: "%s"', $value);
        parent::__construct($message, 0, null);
    }
}