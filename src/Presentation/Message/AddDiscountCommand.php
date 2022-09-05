<?php
declare(strict_types=1);

namespace App\Presentation\Message;

final class AddDiscountCommand implements Command
{
    public function __construct(private int $value, private ?string $sku, private ?string $category){}

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
