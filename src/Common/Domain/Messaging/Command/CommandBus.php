<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging\Command;

interface CommandBus
{
    public function dispatch(Command $command): void;
}
