<?php

declare(strict_types=1);

namespace Tests\Unit\Common\Infrastructure\Messaging\Event;

use App\Common\Domain\Messaging\Event\DomainEvent;
use App\Common\Domain\Messaging\Event\DomainEventBehavior;
use App\Common\Domain\Messaging\Event\DomainEventException;
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
    /**
     * @param string $aggregateRootId
     * @return array<DomainEvent>
     */
    private function events(string $aggregateRootId): array
    {
        return [
            new EventA(
                $aggregateRootId,
                payload: [],
                headers: [
                    DomainEvent::EVENT_ID => 'df126b31-f7c8-4f6a-bca5-63c46b419d01',
                    DomainEvent::EVENT_VERSION => 1,
                ]
            ),
            new EventB(
                $aggregateRootId,
                payload: [],
                headers: [
                    DomainEvent::EVENT_ID => '9334259b-b25d-4c62-8a84-52787544888b',
                    DomainEvent::EVENT_VERSION => 2,
                ]
            ),
            new EventB(
                '34f98149-e1ea-446a-9668-a0ef633f0dc7',
                payload: [],
                headers: [
                    DomainEvent::EVENT_ID => 'd480bdef-8d5c-4644-b55e-3fd9ceef41c8',
                    DomainEvent::EVENT_VERSION => 1,
                ]
            ),
            new EventB(
                $aggregateRootId,
                payload: [],
                headers: [
                    DomainEvent::EVENT_ID => '3b22eb99-021a-4106-aac7-1d9e10322799',
                    DomainEvent::EVENT_VERSION => 3,
                ]
            ),
            new EventA(
                $aggregateRootId,
                payload: [],
                headers: [
                    DomainEvent::EVENT_ID => 'd451864a-350e-4a49-8414-c48c3d9608df',
                    DomainEvent::EVENT_VERSION => 4,
                ]
            ),
            new EventA(
                '34f98149-e1ea-446a-9668-a0ef633f0dc7',
                payload: [],
                headers: [
                    DomainEvent::EVENT_ID => 'e380bdef-7d5c-3544-b55e-3fd9ceef41c8',
                    DomainEvent::EVENT_VERSION => 2,
                ]
            ),
        ];
    }

    /** @test */
    public function can_create_an_inmemory_event_store_object_with_initial_store(): void
    {
        $aggregateRootId = '9cfc4e5f-c515-41f7-8a12-a815f082c06a';

        $store = new InMemoryEventStore(store: $this->events($aggregateRootId));

        $this->assertInstanceOf(EventStore::class, $store);
        $this->assertCount(4, iterator_to_array($store->retrieve($aggregateRootId)->events()));
    }

    /** @test */
    public function can_add_new_domain_events_to_event_store(): void
    {
        $aggregateRootId = '9cfc4e5f-c515-41f7-8a12-a815f082c06a';

        $store = new InMemoryEventStore(store: $this->events($aggregateRootId));

        $newEvents = [
            new EventA(
                $aggregateRootId,
                payload: [],
                headers: [
                    DomainEvent::EVENT_ID => '96a43863-5cea-471d-a2a6-b613e06469d2',
                    DomainEvent::EVENT_VERSION => 5,
                ]
            ),
            new EventB(
                $aggregateRootId,
                payload: [],
                headers: [
                    DomainEvent::EVENT_ID => '3eafc050-55f3-4a75-9ca0-12db8b380300',
                    DomainEvent::EVENT_VERSION => 6,
                ]
            ),
        ];

        $store->persist(...$newEvents);

        /** @var array<DomainEvent> */
        $eventsAfterAddition = iterator_to_array($store->retrieve($aggregateRootId)->events());

        /** @var array<DomainEvent> */
        $eventsAfterAdditionAndVersion = iterator_to_array($store->retrieve($aggregateRootId, 4)->events());

        $this->assertCount(6, $eventsAfterAddition);
        $this->assertCount(2, $eventsAfterAdditionAndVersion);
        $this->assertEquals('96a43863-5cea-471d-a2a6-b613e06469d2', $eventsAfterAdditionAndVersion[0]->id());
        $this->assertEquals('3eafc050-55f3-4a75-9ca0-12db8b380300', $eventsAfterAdditionAndVersion[1]->id());
    }

    /** @test */
    public function throw_an_exceptions_when_persisting_older_versioned_event(): void
    {
        $this->expectException(DomainEventException::class);
        $this->expectExceptionMessage('Domain event with id: 96a43863-5cea-471d-a2a6-b613e06469d2 can\'t be persisted because the current stream has already used the version number: 4.');

        $aggregateRootId = '9cfc4e5f-c515-41f7-8a12-a815f082c06a';

        $store = new InMemoryEventStore(store: $this->events($aggregateRootId));

        $store->persist(
            new EventA(
                $aggregateRootId,
                payload: [],
                headers: [
                    DomainEvent::EVENT_ID => '96a43863-5cea-471d-a2a6-b613e06469d2',
                    DomainEvent::EVENT_VERSION => 4,
                ]
            )
        );
    }

    /** @test */
    public function throw_an_exceptions_when_persisting_future_versioned_event(): void
    {
        $this->expectException(DomainEventException::class);
        $this->expectExceptionMessage('Domain event with id: 96a43863-5cea-471d-a2a6-b613e06469d2 can\'t be persisted because the current stream expect an event with version: 5 and it got 8.');

        $aggregateRootId = '9cfc4e5f-c515-41f7-8a12-a815f082c06a';

        $store = new InMemoryEventStore(store: $this->events($aggregateRootId));

        $store->persist(
            new EventA(
                $aggregateRootId,
                payload: [],
                headers: [
                    DomainEvent::EVENT_ID => '96a43863-5cea-471d-a2a6-b613e06469d2',
                    DomainEvent::EVENT_VERSION => 8,
                ]
            )
        );
    }
}
