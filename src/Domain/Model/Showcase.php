<?php

namespace App\Domain\Model;

use App\Domain\Exception\DuplicateSkuException;
use App\Domain\Exception\ProductNotFoundException;
use App\Domain\Repository\PriceRepositoryInterface;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Infrastructure\Entity\Price;
use App\Infrastructure\Entity\Product;
use App\Presentation\Dto\ProductDto;

class Showcase
{
    /** @var Product[] $products */
    public array $products = [];
    public function __construct(private ProductRepositoryInterface $prodRepo, private PriceRepositoryInterface $priceRepo){}

    /**
     * @throws DuplicateSkuException
     */
    public function createProduct(string $sku, string $name, string $category, string $price): Product
    {
        if($this->prodRepo->findBy(['sku'=>$sku])){
            throw new DuplicateSkuException($sku);
        }
        return Product::create($sku, $name, $category, $price);
    }

    /**
     * @throws DuplicateSkuException
     */
    public function createProductFromDto(ProductDto $dto): Product
    {
        $sku = $dto->sku();
        if(isset($this->products[$sku])){
            throw new DuplicateSkuException($sku);
        }
        $this->products[$sku] = Product::create($sku, $dto->name(), $dto->category(), $dto->price());
        return $this->products[$sku];
    }

    public function add(Product $product, bool $flush = false): void
    {
        $this->prodRepo->add($product, $flush);
    }

    public function removeOldProducts(): void
    {
        $this->prodRepo->deleteAllProducts();
    }

    public function flush(): void
    {
        $this->prodRepo->flush();
    }

    /**
     * @throws ProductNotFoundException
     */
    public function remove(string $sku): void
    {
        $product = $this->prodRepo->findOneBy(['sku'=>$sku]);
        if(! $product){
            throw new ProductNotFoundException($sku);
        }
        $this->prodRepo->remove($product, true);
    }
}