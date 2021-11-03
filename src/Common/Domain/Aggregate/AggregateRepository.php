<?php

declare(strict_types=1);

namespace App\Common\Domain\Aggregate;

use App\Common\Domain\Messaging\Event\DomainEventDecorator;
use App\Common\Domain\Messaging\Event\EventBus;
use App\Common\Domain\Messaging\Event\EventStore;

class AggregateRepository
{
    public function __construct(
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
}
