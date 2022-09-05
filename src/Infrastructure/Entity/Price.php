<?php

namespace App\Infrastructure\Entity;

use App\Infrastructure\Repository\PriceRepository;
use Doctrine\ORM\Mapping as ORM;
use Money\Currency;
use Money\Money;

#[ORM\Entity(repositoryClass: PriceRepository::class)]
class Price
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $original = null;

    #[ORM\Column]
    private ?int $final = null;

    #[ORM\Column(length: 3, options: ["default" => "EUR"])]
    private ?string $currency = 'EUR';

    #[ORM\OneToOne(inversedBy: 'price', cascade: ['remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'price')]
    private ?Discount $discount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginal(): ?int
    {
        return $this->original;
    }

    public function setOriginal(int $original): self
    {
        $this->original = $original;
        $this->final = $original;

        return $this;
    }

    public function getFinal(): ?int
    {
        return $this->final;
    }

    public function setFinal(int $final): self
    {
        $this->final = $final;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(?Discount $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'original'=>$this->getOriginal(),
            'final'=>$this->getFinal(),
            'discount\_percentage'=> $this->getDiscount()? $this->getDiscount()->getValue()."%" : null,
            'currency'=>$this->getCurrency()
        ];
    }

    public function apply(Discount $discount): void
    {
        $original = new Money($this->getOriginal(), new Currency('EUR'));
        $discounted = $original->multiply($discount->getValue())->divide(100);
        $this->setFinal($original->subtract($discounted)->getAmount());
        $this->setDiscount($discount);
    }
}
