<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Messaging\Event;

use App\Common\Domain\Messaging\Event\DomainEvent;
use App\Common\Domain\Messaging\Event\DomainEventDecorator;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

class DomainEventOccurredOnDecorator implements DomainEventDecorator
{
    public function decorate(DomainEvent $event): DomainEvent
    {
        $datetime = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        return $event->withHeader(DomainEvent::EVENT_OCCURED_ON, $datetime->format(DateTimeInterface::ATOM));
    }
}
