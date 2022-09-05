<?php
declare(strict_types=1);

namespace App\Presentation\Message;

use Doctrine\Common\Collections\ArrayCollection;

final class ReplaceDiscountsCommand implements Command
{
    public function __construct(private readonly ArrayCollection $discountDtoCollection){}

    public function discountDtoCollection(): ArrayCollection
    {
        return $this->discountDtoCollection;
    }
}
