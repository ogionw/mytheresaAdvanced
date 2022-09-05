<?php

namespace App\Infrastructure\Entity;

use App\Domain\Exception\InvalidPriceException;
use App\Infrastructure\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $sku = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\OneToOne(mappedBy: 'product', cascade: ['persist', 'remove'])]
    private ?Price $price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): ?Price
    {
        return $this->price;
    }

    public function setPrice(Price $price): self
    {
        // set the owning side of the relation if necessary
        if ($price->getProduct() !== $this) {
            $price->setProduct($this);
        }

        $this->price = $price;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'name'=>$this->getName(),
            'sku'=>$this->getSku(),
            'category'=>$this->getCategory(),
            'price'=>$this->getPrice()->toArray()
        ];
    }

    public function apply(Discount $discount): void
    {
        $this->price->apply($discount);
    }

    public static function create(string $sku, string $name, string $category, int $price): self
    {
        if($price < 1){
            throw new InvalidPriceException($price, $sku);
        }
        return (new Product())
            ->setSku($sku)
            ->setName($name)
            ->setCategory($category)
            ->setPrice((new Price())->setOriginal($price));
    }
}
