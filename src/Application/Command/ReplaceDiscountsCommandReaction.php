<?php
declare(strict_types=1);

namespace App\Application\Command;
use App\Application\Cqrs\CommandHandler;
use App\Application\Cqrs\EventBusInterface;
use App\Domain\Event\NewSaleReceivedEvent;
use App\Presentation\Message\ReplaceDiscountsCommand;

final class ReplaceDiscountsCommandReaction implements CommandHandler
{
    public function __construct(private readonly EventBusInterface $eventBus){}

    public function __invoke(ReplaceDiscountsCommand $command)
    {
        $this->eventBus->dispatch(new NewSaleReceivedEvent($command->discountDtoCollection()));
    }
}
