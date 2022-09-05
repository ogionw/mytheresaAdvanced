<?php
declare(strict_types=1);

namespace App\Presentation\Message;

final class RemoveDiscountCommand implements Command
{
    public function __construct(private readonly ?string $sku, private readonly ?string $category){}

    public function sku(): string
    {
        return $this->sku;
    }

    public function category(): string
    {
        return $this->category;
    }
}
