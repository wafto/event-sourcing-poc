<?php

declare(strict_types=1);

namespace Tests\Unit\Common\Infrastructure\Messaging\Event;

use App\Common\Domain\Messaging\Event\DomainEvent;
use App\Common\Domain\Messaging\Event\DomainEventBehavior;
use App\Common\Domain\Messaging\Event\EventStream;
use App\Common\Infrastructure\Messaging\Event\ArrayEventStream;
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

class ArrayEventStreamTest extends TestCase
{
    /** @test */
    public function can_create_an_array_event_stream_object(): void
    {
        $aggregateRootId = '9cfc4e5f-c515-41f7-8a12-a815f082c06a';

        $stream = new ArrayEventStream($aggregateRootId, events: []);

        $this->assertInstanceOf(EventStream::class, $stream);
    }

    /** @test */
    public function events_can_be_yielded_in_order(): void
    {
        $aggregateRootId = '9cfc4e5f-c515-41f7-8a12-a815f082c06a';

        $events = [
            new EventA($aggregateRootId, payload: []),
            new EventB($aggregateRootId, payload: []),
            new EventB($aggregateRootId, payload: []),
            new EventA($aggregateRootId, payload: []),
        ];

        $stream = new ArrayEventStream($aggregateRootId, events: $events);

        $streamEvents = iterator_to_array($stream->events());

        $this->assertCount(count($events), $streamEvents);
        $this->assertEquals($events[0], $streamEvents[0]);
        $this->assertEquals($events[1], $streamEvents[1]);
        $this->assertEquals($events[2], $streamEvents[2]);
        $this->assertEquals($events[3], $streamEvents[3]);
    }
}
