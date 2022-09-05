<?php
declare(strict_types=1);

namespace App\Domain\Listener;
use App\Application\Cqrs\EventHandler;
use App\Domain\Event\DiscountCanceledEvent;
use App\Domain\Model\Store;

final class DiscountCanceledListener implements EventHandler
{
    public function __construct(private readonly Store $store){}

    public function __invoke(DiscountCanceledEvent $event)
    {
        $this->store->removeDiscount($event->sku(), $event->category());
    }
}
