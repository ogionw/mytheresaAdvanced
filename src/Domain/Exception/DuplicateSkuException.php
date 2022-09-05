<?php

namespace App\Domain\Exception;

use Exception;

class DuplicateSkuException extends Exception
{
    public function __construct(string $sku = '')
    {
        $message = sprintf('Duplicate sku: %s', $sku);
        parent::__construct($message, 0, null);
    }
}