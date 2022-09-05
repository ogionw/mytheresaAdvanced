<?php

namespace App\Domain\Model;


use App\Domain\Exception\DuplicateSkuException;
use Doctrine\Common\Collections\ArrayCollection;

class Store
{
    public function __construct(private Showcase $showcase, private Discounter $discounter){}

    /**
     * @throws DuplicateSkuException
     */
    public function addProduct(string $sku, string $name, string $category, int $price): void
    {
        $product = $this->showcase->createProduct($sku, $name, $category, $price);
        $this->discounter->applyDiscount($product);
        $this->showcase->add($product, true);
    }

    /**
     * @throws DuplicateSkuException
     */
    public function replaceProducts(ArrayCollection $productDtoArray): void
    {
        foreach ($productDtoArray as $productDto){
            $product = $this->showcase->createProductFromDto($productDto);
            $this->discounter->applyDiscount($product);
            $this->showcase->add($product);
        }
        $this->showcase->removeOldProducts();
        $this->showcase->flush();
    }

    public function removeProduct(string $sku)
    {
        $this->showcase->remove($sku);
    }

    public function replaceDiscounts(ArrayCollection $discountDtoCollection)
    {
    }

    public function addDiscount(string $sku, string $category, int $value)
    {
    }

    public function removeDiscount(string $sku, string $category)
    {
    }
}