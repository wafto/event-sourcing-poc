<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging\Event;

interface DomainEventDecorator
{
    public function decorate(DomainEvent $event): DomainEvent;
}
