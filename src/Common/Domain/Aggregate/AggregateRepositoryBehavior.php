<?php

declare(strict_types=1);

namespace App\Common\Domain\Aggregate;

use App\Common\Domain\Messaging\Event\DomainEventDecorator;
use App\Common\Domain\Messaging\Event\EventBus;
use App\Common\Domain\Messaging\Event\EventStore;

class AggregateRepositoryBehavior implements AggregateRepository
{
    /**
     * @param class-string<AggregateRoot> $classname
     * @param EventStore $store
     * @param EventBus $bus
     * @param DomainEventDecorator $decorator
     */
    public function __construct(
        private string $classname,
        private EventStore $store,
        private EventBus $bus,
        private DomainEventDecorator $decorator,
    ) {
    }

    public function persist(AggregateRoot $aggregate): void
    {
        $events = array_map(
            fn ($event) => $this->decorator->decorate($event),
            $aggregate->pullDomainEvents()
        );

        $this->store->persist(...$events);
        $this->bus->publish(...$events);
    }

    public function retrieve(AggregateRootId $aggregateRootId): ?AggregateRoot
    {
        $stream = $this->store->retrieve($aggregateRootId->value());
        return $this->classname::reconstitute($stream);
    }
}
