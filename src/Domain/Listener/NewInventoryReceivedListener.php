<?php
declare(strict_types=1);

namespace App\Domain\Listener;
use App\Application\Cqrs\EventHandler;
use App\Domain\Event\NewInventoryReceivedEvent;
use App\Domain\Exception\DuplicateSkuException;
use App\Domain\Model\Store;

final class NewInventoryReceivedListener implements EventHandler
{
    public function __construct(private Store $store){}

    /**
     * @throws DuplicateSkuException
     */
    public function __invoke(NewInventoryReceivedEvent $event)
    {
        $this->store->replaceProducts($event->productDtoCollection());
    }
}
