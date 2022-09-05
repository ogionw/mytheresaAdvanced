<?php

namespace App\Presentation\Exception;

use Exception;

class MissingProductJsonException extends Exception
{
    public function __construct()
    {
        $message = 'Failed to find products in json';
        parent::__construct($message, 0, null);
    }
}