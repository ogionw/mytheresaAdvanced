<?php
declare(strict_types=1);

namespace App\Presentation\Message;

final class RemoveProductCommand implements Command
{
    public function __construct(private readonly string $sku){}

    public function sku(): string
    {
        return $this->sku;
    }
}
