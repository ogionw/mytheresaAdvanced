<?php

namespace App\Domain\Event;

use App\Application\Cqrs\DomainEventInterface;
use Doctrine\Common\Collections\ArrayCollection;

class NewInventoryReceivedEvent implements DomainEventInterface
{
    public function __construct(private readonly ArrayCollection $productDtoCollection){}

    public function productDtoCollection(): ArrayCollection
    {
        return $this->productDtoCollection;
    }

}