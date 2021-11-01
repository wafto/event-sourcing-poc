<?php

declare(strict_types=1);

namespace Tests\Unit\Common\Infrastructure\Messaging\Event;

use App\Common\Domain\Messaging\Event\DomainEvent;
use App\Common\Domain\Messaging\Event\DomainEventBehavior;
use App\Common\Domain\Messaging\Event\EventStore;
use App\Common\Infrastructure\Messaging\Event\InMemoryEventStore;
use PHPUnit\Framework\TestCase;

final class EventA implements DomainEvent
{
    use DomainEventBehavior;

    public static function type(): string
    {
        return 'acme.test.1.event.stub.a';
    }
}

final class EventB implements DomainEvent
{
    use DomainEventBehavior;

    public static function type(): string
    {
        return 'acme.test.1.event.stub.b';
    }
}

class InMemoryEventStoreTest extends TestCase
{
    /** @test */
    public function can_create_an_inmemory_event_store_object(): void
    {
        $store = new InMemoryEventStore([]);

        $this->assertInstanceOf(EventStore::class, $store);
    }

    /** @testta */
    public function can_add_new_domain_events_to_event_store(): void
    {
        /** Arrange */
        $aggregateRootId = '9cfc4e5f-c515-41f7-8a12-a815f082c06a';

        $store = new InMemoryEventStore(store: [
            new EventA($aggregateRootId, payload: [], headers: [DomainEvent::EVENT_VERSION => 1]),
            new EventB($aggregateRootId, payload: [], headers: [DomainEvent::EVENT_VERSION => 2]),
            new EventB($aggregateRootId, payload: [], headers: [DomainEvent::EVENT_VERSION => 3]),
            new EventA($aggregateRootId, payload: [], headers: [DomainEvent::EVENT_VERSION => 4]),
        ]);

        /** Act */


        /** Assert */
    }
}
