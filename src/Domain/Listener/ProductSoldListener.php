<?php
declare(strict_types=1);

namespace App\Domain\Listener;
use App\Application\Cqrs\EventHandler;
use App\Domain\Event\ProductSoldEvent;
use App\Domain\Model\Store;

final class ProductSoldListener implements EventHandler
{
    public function __construct(private readonly Store $store){}

    public function __invoke(ProductSoldEvent $event)
    {
        $this->store->removeProduct($event->sku());
    }
}
