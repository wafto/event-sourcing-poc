<?php

declare(strict_types=1);

namespace Tests\Unit\Common\Infrastructure\Messaging\Event;

use App\Common\Infrastructure\Messaging\Event\DomainEventIdDecorator;
use PHPUnit\Framework\TestCase;

class DomainEventIdDecoratorTest extends TestCase
{
    /** @test */
    public function can_decorate_domain_event_with_id(): void
    {
        $decorator = new DomainEventIdDecorator();

        $event = new EventStubA('9cfc4e5f-c515-41f7-8a12-a815f082c06a', payload: []);

        $this->assertNull($event->id());

        $decorated = $decorator->decorate($event);

        $this->assertNotNull($decorated->id());
    }
}
