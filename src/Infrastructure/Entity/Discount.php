<?php

namespace App\Infrastructure\Entity;

use App\Infrastructure\Repository\DiscountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiscountRepository::class)]
class Discount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $sku = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $category = null;

    #[ORM\Column]
    private ?int $value = null;

    #[ORM\OneToMany(mappedBy: 'discount', targetEntity: Price::class, cascade: ['PERSIST'])]
    private Collection $price;

    public function __construct()
    {
        $this->price = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection<int, Price>
     */
    public function getPrice(): Collection
    {
        return $this->price;
    }

    public function addPrice(Price $price): self
    {
        if (!$this->price->contains($price)) {
            $this->price->add($price);
            $price->setDiscount($this);
        }

        return $this;
    }

    public function removePrice(Price $price): self
    {
        if ($this->price->removeElement($price)) {
            // set the owning side to null (unless already changed)
            if ($price->getDiscount() === $this) {
                $price->setDiscount(null);
            }
        }

        return $this;
    }
}
