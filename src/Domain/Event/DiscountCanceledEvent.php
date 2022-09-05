<?php

namespace App\Domain\Event;

use App\Application\Cqrs\DomainEventInterface;

class DiscountCanceledEvent implements DomainEventInterface
{
    public function __construct(private readonly string $sku, private readonly string $category){}

    public function sku(): string
    {
        return $this->sku;
    }

    public function category(): string
    {
        return $this->category;
    }
}