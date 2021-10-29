<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Messaging\Event;

use App\Common\Domain\Messaging\Event\EventStream;
use App\Common\Domain\Messaging\Event\DomainEvent;
use Generator;

final class ArrayEventStream implements EventStream
{
    /**
     * @param string $aggregateRootId
     * @param array<DomainEvent> $events
     */
    public function __construct(
        private string $aggregateRootId,
        private array $events,
    ) {
    }

    public function aggregateRootId(): string
    {
        return $this->aggregateRootId;
    }

    public function events(): Generator
    {
        foreach ($this->events as $event) {
            yield $event;
        }
    }
}
