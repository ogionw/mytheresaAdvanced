<?php
declare(strict_types=1);

namespace App\Application\Command;
use App\Application\Cqrs\CommandHandler;
use App\Application\Cqrs\EventBusInterface;
use App\Domain\Event\NewInventoryReceivedEvent;
use App\Presentation\Message\ReplaceProductsCommand;

final class ReplaceProductsCommandReaction implements CommandHandler
{
    public function __construct(private readonly EventBusInterface $eventBus){}

    public function __invoke(ReplaceProductsCommand $command)
    {
        $this->eventBus->dispatch(new NewInventoryReceivedEvent($command->productDtoCollection()));
    }
}
