<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Messaging\Event;

use App\Common\Domain\Messaging\Event\DomainEvent;
use App\Common\Domain\Messaging\Event\DomainEventDecorator;
use Ramsey\Uuid\Uuid;

class DomainEventIdDecorator implements DomainEventDecorator
{
    public function decorate(DomainEvent $event): DomainEvent
    {
        return $event->withHeader(DomainEvent::EVENT_ID, Uuid::uuid4()->toString());
    }
}
