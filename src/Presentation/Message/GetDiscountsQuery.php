<?php
declare(strict_types=1);

namespace App\Presentation\Message;

final class GetDiscountsQuery implements Query
{
    public function __construct(private readonly ?string $sku, private readonly ?string $category){}

    public function sku(): ?int
    {
        return $this->sku;
    }

    public function category(): ?string
    {
        return $this->category;
    }
}
