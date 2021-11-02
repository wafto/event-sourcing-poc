<?php

declare(strict_types=1);

namespace Tests\Unit\Common\Infrastructure\Messaging\Event;

use App\Common\Domain\Messaging\Event\DomainEvent;
use App\Common\Infrastructure\Messaging\Event\InMemoryEventBus;
use PHPUnit\Framework\TestCase;

class InMemoryEventBusTest extends TestCase
{
    /** @test */
    public function when_publishing_an_event_it_should_listen_to_corresponding_subscriber(): void
    {
        $aggregateRootId = '9cfc4e5f-c515-41f7-8a12-a815f082c06a';
        $subscriber = new LoggingEventSubscriber();
        $bus = new InMemoryEventBus($subscriber);

        $bus->publish(
            new EventStubA(
                $aggregateRootId,
                payload: [],
                headers: [
                    DomainEvent::EVENT_ID => 'df126b31-f7c8-4f6a-bca5-63c46b419d01',
                    DomainEvent::EVENT_VERSION => 1,
                ]
            )
        );

        $this->assertEquals(['onEventStubA', EventStubA::class], $subscriber->log[0]);
        $this->assertEquals(['onEventAonEventC', EventStubA::class], $subscriber->log[1]);
    }
}
