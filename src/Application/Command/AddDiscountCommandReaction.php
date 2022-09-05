<?php
declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Cqrs\CommandHandler;
use App\Domain\Model\Transportation;
use App\Presentation\Message\AddDiscountCommand;

final class AddDiscountCommandReaction implements CommandHandler
{
    public function __construct(){}

    public function __invoke(AddDiscountCommand $command)
    {
    }
}
