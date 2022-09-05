<?php
declare(strict_types=1);

namespace App\Presentation\Message;

final class AddProductCommand implements Command
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
