<?php
declare(strict_types=1);

namespace App\Domain\Listener;
use App\Application\Cqrs\EventHandler;
use App\Domain\Event\NewSaleReceivedEvent;
use App\Domain\Model\Store;

final class NewSaleReceivedListener implements EventHandler
{
    public function __construct(private Store $store){}

    public function __invoke(NewSaleReceivedEvent $event)
    {
        $this->store->replaceDiscounts($event->discountDtoCollection());
    }
}
