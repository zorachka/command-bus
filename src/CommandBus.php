<?php

declare(strict_types=1);

namespace Zorachka\CommandBus;

interface CommandBus
{
    /**
     * Handle command.
     */
    public function handle(object $command): void;
}
