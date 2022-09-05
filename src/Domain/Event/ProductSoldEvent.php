<?php

namespace App\Domain\Event;

use App\Application\Cqrs\DomainEventInterface;

class ProductSoldEvent implements DomainEventInterface
{
    public function __construct(private readonly string $sku){}

    public function sku(): string
    {
        return $this->sku;
    }
}