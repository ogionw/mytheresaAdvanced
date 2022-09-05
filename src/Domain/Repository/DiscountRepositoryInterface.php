<?php

namespace App\Domain\Repository;

use App\Infrastructure\Entity\Discount;
use App\Infrastructure\Entity\Product;

interface DiscountRepositoryInterface
{
    public function getMaxDiscountsForProduct(Product $product): ?Discount;
}