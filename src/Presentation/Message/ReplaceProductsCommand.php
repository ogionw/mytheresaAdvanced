<?php
declare(strict_types=1);

namespace App\Presentation\Message;

use Doctrine\Common\Collections\ArrayCollection;

final class ReplaceProductsCommand implements Command
{
    public function __construct(private readonly ArrayCollection $productDtoCollection){}

    public function productDtoCollection(): ArrayCollection
    {
        return $this->productDtoCollection;
    }
}
