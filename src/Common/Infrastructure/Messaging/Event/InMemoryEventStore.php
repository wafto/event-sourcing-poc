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
     * @var array<DomainEvent> $store
     */
    private array $store = [];

    /**
     * @param array<DomainEvent> $store
     */
    public function __construct(array $store = [])
    {
        $this->persist(...$store);
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
        $lastVersionFromAggregateRoot = $this->lastVersionForAggregateRootId($event->aggregateRootId());

        if ($event->version() <= $lastVersionFromAggregateRoot) {
            throw DomainEventException::cantPersistOlderVersioned($event);
        }

        $expectedVersion = $lastVersionFromAggregateRoot + 1;

        if ($event->version() != $expectedVersion) {
            throw DomainEventException::cantPersistWithMissingVersion($event, $expectedVersion);
        }

        $this->store[] = $event;
    }

    /**
     * param string $aggregateRootId
     * @return int
     */
    private function lastVersionForAggregateRootId(string $aggregateRootId): int
    {
        $storeCount = count($this->store);

        for ($i = $storeCount - 1; $i >= 0; $i -= 1) {
            $event = $this->store[$i];

            if ($event->aggregateRootId() === $aggregateRootId) {
                return $event->version();
            }
        }

        return 0;
    }
}
