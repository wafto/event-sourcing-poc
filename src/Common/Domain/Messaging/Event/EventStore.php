<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging\Event;

interface EventStore
{
    public function publish(DomainEvent ...$events): void;

    public function retrieve(string $aggregateRootId, int $afterVersion = 0): EventStream;
}
