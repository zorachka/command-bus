<?php

declare(strict_types=1);

namespace Zorachka\Framework\CommandBus\Tactician;

use League\Tactician\CommandBus as LeagueTacticianCommandBus;
use Zorachka\Framework\CommandBus\CommandBus;

final class TacticianCommandBus implements CommandBus
{
    private LeagueTacticianCommandBus $commandBus;

    public function __construct(LeagueTacticianCommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @inheritDoc
     */
    public function handle(object $command)
    {
        return $this->commandBus->handle($command);
    }
}
