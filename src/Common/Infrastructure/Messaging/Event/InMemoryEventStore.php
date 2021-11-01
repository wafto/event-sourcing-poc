<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Messaging\Event;

use App\Common\Domain\Messaging\Event\DomainEvent;
use App\Common\Domain\Messaging\Event\DomainEventException;
use App\Common\Domain\Messaging\Event\EventStream;
use App\Common\Domain\Messaging\Event\EventStore;
use App\Common\Infrastructure\Messaging\Event\ArrayEventStream;

final class InMemoryEventStore implements EventStore
{
    /**
     * @param array<DomainEvent> $store
     */
    public function __construct(private array $store = [])
    {
    }

    /**
     *  throws DomainEventException
     */
    public function persist(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->add($event);
        }
    }

    public function retrieve(string $aggregateRootId, int $afterVersion = 0): EventStream
    {
        $result = [];

        foreach ($this->store as $event) {
            if ($aggregateRootId === $event->aggregateRootId() && $event->version() > $afterVersion) {
                $result[] = $event;
            }
        }

        return new ArrayEventStream($aggregateRootId, $result);
    }

    /**
     *  throws DomainEventException
     */
    private function add(DomainEvent $event): void
    {
        $lastEvent = $this->store[count($this->store) - 1];

        if ($event->version() <= $lastEvent->version()) {
            throw DomainEventException::cantPersistOlderVersioned($event);
        }

        $expectedVersion = $lastEvent->version() + 1;

        if ($event->version() != $expectedVersion) {
            throw DomainEventException::cantPersistWithMissingVersion($event, $expectedVersion);
        }

        $this->store[] = $event;
    }
}
