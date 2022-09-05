<?php

namespace App\Domain\Model;

use App\Domain\Repository\DiscountRepositoryInterface;
use App\Infrastructure\Entity\Product;

class Discounter
{
    /** @var Product[] $products */
    public array $products = [];
    public function __construct(private DiscountRepositoryInterface $discountRepo){}

    public function applyDiscount(Product $product): void
    {
        $discount = $this->discountRepo->getMaxDiscountsForProduct($product);
        if($discount){
            $product->apply($discount);
        }
    }
}