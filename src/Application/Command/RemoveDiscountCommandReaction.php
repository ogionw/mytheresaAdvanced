<?php
declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Cqrs\CommandHandler;
use App\Application\Cqrs\EventBusInterface;
use App\Domain\Event\DiscountCanceledEvent;
use App\Presentation\Message\RemoveDiscountCommand;

final class RemoveDiscountCommandReaction implements CommandHandler
{
    public function __construct(private readonly EventBusInterface $eventBus){}

    public function __invoke(RemoveDiscountCommand $command)
    {
        $this->eventBus->dispatch(new DiscountCanceledEvent($command->sku(), $command->category()));
    }
}
