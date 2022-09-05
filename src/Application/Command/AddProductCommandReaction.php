<?php
declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Cqrs\CommandHandler;
use App\Application\Cqrs\EventBusInterface;
use App\Domain\Event\NewProductReceivedEvent;
use App\Presentation\Message\AddProductCommand;

final class AddProductCommandReaction implements CommandHandler
{
    public function __construct(private EventBusInterface $eventBus){}

    public function __invoke(AddProductCommand $command)
    {
        $this->eventBus->dispatch(
            new NewProductReceivedEvent($command->sku(), $command->name(), $command->category(), $command->price())
        );
    }
}
