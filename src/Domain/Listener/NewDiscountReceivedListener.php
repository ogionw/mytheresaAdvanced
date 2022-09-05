<?php
declare(strict_types=1);

namespace App\Domain\Listener;
use App\Application\Cqrs\EventHandler;
use App\Domain\Event\NewDiscountReceivedEvent;
use App\Domain\Model\Store;

final class NewDiscountReceivedListener implements EventHandler
{
    public function __construct(private Store $store){}

    public function __invoke(NewDiscountReceivedEvent $event)
    {
        $this->store->addDiscount($event->sku(), $event->category(),$event->value());
    }
}
