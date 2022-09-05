<?php

namespace App\Domain\Event;

use App\Application\Cqrs\DomainEventInterface;
use Doctrine\Common\Collections\ArrayCollection;

class NewSaleReceivedEvent implements DomainEventInterface
{
    public function __construct(private readonly ArrayCollection $discountDtoCollection){}

    public function discountDtoCollection(): ArrayCollection
    {
        return $this->discountDtoCollection;
    }

}