<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Messaging\Event;

use App\Common\Domain\Messaging\Event\DomainEvent;
use App\Common\Domain\Messaging\Event\EventBus;
use App\Common\Domain\Messaging\Event\EventSubscriber;

final class InMemoryEventBus implements EventBus
{
    /**
     * @var array<string, array<EventSubscriberReflection>>
     */
    private array $subscriber = [];

    public function __construct(EventSubscriber ...$subscriber)
    {
        foreach ($subscriber as $subscriber) {
            $reflection = new EventSubscriberReflection($subscriber);
        }
    }

    public function publish(DomainEvent ...$events): void
    {
    }
}
