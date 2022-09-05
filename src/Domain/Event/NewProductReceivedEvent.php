<?php

namespace App\Domain\Event;

use App\Application\Cqrs\DomainEventInterface;

class NewProductReceivedEvent implements DomainEventInterface
{
    public function __construct(
        private readonly string $sku,
        private readonly string $name,
        private readonly string $category,
        private readonly int $price
    ){}

    public function sku(): string
    {
        return $this->sku;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function category(): string
    {
        return $this->category;
    }

    public function price(): int
    {
        return $this->price;
    }
}