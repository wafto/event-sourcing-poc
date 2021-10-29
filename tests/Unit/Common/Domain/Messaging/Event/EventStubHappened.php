<?php

declare(strict_types=1);

namespace Tests\Unit\Common\Domain\Messaging\Event;

use App\Common\Domain\Messaging\Event\DomainEvent;
use App\Common\Domain\Messaging\Event\DomainEventBehavior;

class EventStubHappened implements DomainEvent
{
    use DomainEventBehavior;

    public function __construct(
        string $eventId,
        int $eventVersion,
        string $eventOccurredOn,
        string $aggregateRootId,
        array $payload,
        array $headers = [],
    ) {
        $this->headers = $headers;
        $this->payload = $payload;
        $this->setId($eventId);
        $this->setVersion($eventVersion);
        $this->setOccurredOn($eventOccurredOn);
        $this->setAggregateRootId($aggregateRootId);
    }

    public static function type(): string
    {
        return 'acme.test.1.event.stub.happened';
    }

    public static function fromArray(array $data): DomainEvent
    {
        static::validateType($data['headers'][DomainEvent::EVENT_TYPE], static::type());

        return new static(
            eventId: (string) $data['headers'][DomainEvent::EVENT_ID],
            eventVersion: (int) $data['headers'][DomainEvent::EVENT_VERSION],
            eventOccurredOn: (string) $data['headers'][DomainEvent::EVENT_OCCURED_ON],
            aggregateRootId: (string) $data['headers'][DomainEvent::AGGREGATE_ROOT_ID],
            payload: (array) $data['payload'],
            headers: (array) $data['headers'],
        );
    }
}
