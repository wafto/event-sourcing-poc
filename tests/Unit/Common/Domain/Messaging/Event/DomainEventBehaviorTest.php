<?php

declare(strict_types=1);

namespace Tests\Unit\Common\Domain\Messaging\Event;

use App\Common\Domain\Messaging\Event\DomainEvent;
use App\Common\Domain\Messaging\Event\DomainEventBehavior;
use PHPUnit\Framework\TestCase;

final class EventStubHappened implements DomainEvent
{
    use DomainEventBehavior;

    public static function type(): string
    {
        return 'acme.test.1.event.stub.happened';
    }
}

class DomainEventBehaviorTest extends TestCase
{
    /** @test */
    public function can_create_domain_events_using_the_combination_of_interface_and_trait(): void
    {
        $event = new EventStubHappened('9cfc4e5f-c515-41f7-8a12-a815f082c06a', []);

        $this->assertInstanceOf(DomainEvent::class, $event);
    }

    /** @test */
    public function domain_event_should_always_be_initialized_with_id_occured_on_version_and_aggregate_id_headers(): void
    {
        $event = new EventStubHappened('9cfc4e5f-c515-41f7-8a12-a815f082c06a', []);
        $headers = $event->headers();

        $this->assertArrayHasKey(DomainEvent::EVENT_TYPE, $headers);
        $this->assertArrayHasKey(DomainEvent::EVENT_ID, $headers);
        $this->assertArrayHasKey(DomainEvent::EVENT_OCCURED_ON, $headers);
        $this->assertArrayHasKey(DomainEvent::EVENT_VERSION, $headers);
        $this->assertArrayHasKey(DomainEvent::AGGREGATE_ROOT_ID, $headers);
    }

    /** @test */
    public function domain_event_can_be_exported_to_array_and_rebuilded_from_the_same_array(): void
    {
        $original = new EventStubHappened('9cfc4e5f-c515-41f7-8a12-a815f082c06a', [
            'numeric' => 1,
            'alpha' => 'hello',
            'float' => 3.1416,
            'array' => ['a', 'b', 'c'],
            'collection' => [
                'x' => 'y',
                'm' => 'n',
            ],
            'nullable' => null,
        ]);

        $duplicated = EventStubHappened::fromArray($original->toArray());

        $this->assertEquals($original->toArray(), $duplicated->toArray());
    }

    /** @test */
    public function with_header_method_returns_a_new_instance_with_the_given_header_change(): void
    {
        $original = new EventStubHappened('9cfc4e5f-c515-41f7-8a12-a815f082c06a', []);
        $copy = $original->withHeader(DomainEvent::EVENT_VERSION, $original->version() + 1);

        $this->assertNotEquals($original->toArray(), $copy->toArray());
        $this->assertEquals($original->version() + 1, $copy->version());
    }
}
