<?php

declare(strict_types=1);

namespace Zorachka\CommandBus\Tactician;

use League\Tactician\CommandBus as LeagueTacticianCommandBus;
use Zorachka\CommandBus\CommandBus;

final class TacticianCommandBus implements CommandBus
{
    private LeagueTacticianCommandBus $commandBus;

    public function __construct(LeagueTacticianCommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle(object $command): void
    {
        $this->commandBus->handle($command);
    }
}
