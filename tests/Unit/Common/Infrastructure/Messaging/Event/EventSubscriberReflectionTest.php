<?php

declare(strict_types=1);

namespace Tests\Unit\Common\Infrastructure\Messaging\Event;

use App\Common\Infrastructure\Messaging\Event\EventSubscriberReflection;
use PHPUnit\Framework\TestCase;

class EventSubscriberReflectionTest extends TestCase
{
    /** @test */
    public function it_should_resolve_events_when_constructed(): void
    {
        $reflection = new EventSubscriberReflection(new LoggingEventSubscriber());

        $listenersForEventA = $reflection->listenersFor(EventStubA::class);
        $listenersForEventB = $reflection->listenersFor(EventStubB::class);
        $listenersForEventC = $reflection->listenersFor(EventStubC::class);

        $this->assertEquals(['onEventStubA', 'onEventAonEventC'], $listenersForEventA);
        $this->assertEquals([], $listenersForEventB);
        $this->assertEquals(['onEventAonEventC'], $listenersForEventC);
    }
}
