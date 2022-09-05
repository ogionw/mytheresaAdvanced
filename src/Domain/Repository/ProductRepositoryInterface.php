<?php

namespace App\Domain\Repository;

interface ProductRepositoryInterface
{
    public function findFiveProductsByFilters(?int $priceLessThan, ?string $category);

    public function deleteAllProducts();
}