<?php
declare(strict_types=1);

namespace App\Domain\Listener;
use App\Application\Cqrs\EventHandler;
use App\Domain\Event\NewProductReceivedEvent;
use App\Domain\Model\Store;

final class NewProductReceivedListener implements EventHandler
{
    public function __construct(private Store $store){}

    public function __invoke(NewProductReceivedEvent $event)
    {
        $this->store->addProduct($event->sku(), $event->name(), $event->category(),$event->price());
    }
}
