<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Messaging\Event;

use App\Common\Domain\Messaging\Event\DomainEvent;
use App\Common\Domain\Messaging\Event\DomainEventDecorator;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

final class DomainEventOccurredOnDecorator implements DomainEventDecorator
{
    public function __construct(private ?DomainEventDecorator $next = null)
    {
    }

    public function decorate(DomainEvent $event): DomainEvent
    {
        $datetime = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        $event = $event->withHeader(DomainEvent::EVENT_OCCURED_ON, $datetime->format(DateTimeInterface::ATOM));

        return null === $this->next ? $event : $this->next->decorate($event);
    }
}
