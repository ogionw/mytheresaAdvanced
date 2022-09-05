<?php
declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Cqrs\CommandHandler;
use App\Application\Cqrs\EventBusInterface;
use App\Domain\Event\ProductSoldEvent;
use App\Presentation\Message\RemoveProductCommand;

final class RemoveProductCommandReaction implements CommandHandler
{
    public function __construct(private readonly EventBusInterface $eventBus){}

    public function __invoke(RemoveProductCommand $command)
    {
        $this->eventBus->dispatch(new ProductSoldEvent($command->sku()));
    }
}
