<?php
declare(strict_types=1);

namespace App\Presentation\Dto;

final class DiscountDto
{
    public function __construct(
        private readonly string $sku,
        private readonly string $category,
        private readonly int $value
    ){}

    public function sku(): string
    {
        return $this->sku;
    }

    public function category(): string
    {
        return $this->category;
    }

    public function value(): int
    {
        return $this->value;
    }
}
