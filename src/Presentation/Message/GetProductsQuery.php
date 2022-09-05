<?php
declare(strict_types=1);

namespace App\Presentation\Message;

final class GetProductsQuery implements Query
{
    public function __construct(private readonly ?int $priceLessThan, private readonly ?string $category){}

    public function priceLessThan(): ?int
    {
        return $this->priceLessThan;
    }

    public function category(): ?string
    {
        return $this->category;
    }
}
