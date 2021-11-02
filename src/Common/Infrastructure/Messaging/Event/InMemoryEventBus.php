<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Messaging\Event;

use App\Common\Domain\Messaging\Event\DomainEvent;
use App\Common\Domain\Messaging\Event\EventBus;
use App\Common\Domain\Messaging\Event\EventSubscriber;

final class InMemoryEventBus implements EventBus
{
    /**
     * @var array<EventSubscriberReflection>
     */
    private array $subscribers = [];

    public function __construct(EventSubscriber ...$subscribers)
    {
        $this->subscribers = array_map(
            fn (EventSubscriber $subscriber) => new EventSubscriberReflection($subscriber),
            $subscribers
        );
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            foreach ($this->subscribers as $reflection) {
                foreach ($reflection->listenersFor($event::class) as $method) {
                    $reflection->subscriber()->$method($event);
                }
            }
        }
    }
}
