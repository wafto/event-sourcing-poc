<?php

declare(strict_types=1);

namespace Tests\Unit\Common\Domain\Messaging\Event;

use App\Common\Domain\Messaging\Event\DomainEvent;
use App\Common\Domain\Messaging\Event\DomainEventBehavior;

final class EventStubHappened implements DomainEvent
{
    use DomainEventBehavior;

    public static function type(): string
    {
        return 'acme.test.1.event.stub.happened';
    }
}
