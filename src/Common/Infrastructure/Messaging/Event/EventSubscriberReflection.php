<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Messaging\Event;

use App\Common\Domain\Messaging\Event\EventSubscriber;
use App\Common\Domain\Messaging\Event\ListensTo;
use ReflectionClass;

final class EventSubscriberReflection
{
    public function __construct(
        private EventSubscriber $subscriber,
    ) {
    }

    public function subscriber(): EventSubscriber
    {
        return $this->subscriber;
    }

    /**
     * @template T of array{class-string<DomainEvent>, array<class-string<EventSubscriber>, string>}
     * @return array<T>
     */
    public function listensTo(): array
    {
        $reflection = new ReflectionClass($this->subscriber());
        $listeners = [];

        foreach ($reflection->getMethods() as $method) {
            $attributes = $method->getAttributes(ListensTo::class);

            foreach ($attributes as $attribute) {
                $listener = $attribute->newInstance();

                $listeners[] = [
                    $listener->event(),
                    [$this->subscriber()::class, $method->getName()]
                ];
            }
        }

        return $listeners;
    }
}
