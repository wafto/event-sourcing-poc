<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Messaging\Event;

use App\Common\Domain\Messaging\Event\DomainEvent;
use App\Common\Domain\Messaging\Event\DomainEventDecorator;
use Ramsey\Uuid\Uuid;

final class DomainEventIdDecorator implements DomainEventDecorator
{
    public function __construct(private ?DomainEventDecorator $next = null)
    {
    }

    public function decorate(DomainEvent $event): DomainEvent
    {
        $event = $event->withHeader(DomainEvent::EVENT_ID, Uuid::uuid4()->toString());

        return null === $this->next ? $event : $this->next->decorate($event);
    }
}
