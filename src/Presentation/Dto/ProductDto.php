<?php
declare(strict_types=1);

namespace App\Presentation\Dto;

use App\Presentation\Exception\NotIntegerPriceException;

final class ProductDto
{
    private string $sku;
    private string $name;
    private string $category;
    private int $price;

    /**
     * @throws NotIntegerPriceException
     */
    public function __construct(string $sku, string $name, string $category, mixed $price)
    {
        $this->sku = $sku;
        $this->category = $category;
        $this->name = $name;
        if(! is_int($price)){
            throw new NotIntegerPriceException((string)$price);
        }
        $this->price = $price;
    }

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
