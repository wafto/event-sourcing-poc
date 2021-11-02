<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Messaging\Event;

use App\Common\Domain\Messaging\Event\EventSubscriber;
use App\Common\Domain\Messaging\Event\ListensTo;
use ReflectionClass;

final class EventSubscriberReflection
{
    /**
     * @var array<array{string, string}>
     */
    private array $listeners = [];

    public function __construct(
        private EventSubscriber $subscriber,
    ) {
        $this->resolveListeners();
    }

    public function subscriber(): EventSubscriber
    {
        return $this->subscriber;
    }

    /**
     * @return array<string>
     */
    public function listenersFor(string $event): array
    {
        return array_values(
            array_map(
                fn ($data) => $data[1],
                array_filter($this->listeners, fn ($data) => $data[0] === $event)
            )
        );
    }

    private function resolveListeners(): void
    {
        $listeners = [];
        $reflection = new ReflectionClass($this->subscriber());

        foreach ($reflection->getMethods() as $method) {
            $attributes = $method->getAttributes(ListensTo::class);

            foreach ($attributes as $attribute) {
                $listener = $attribute->newInstance();
                $listeners[] = [$listener->event(), $method->getName()];
            }
        }

        $this->listeners = $listeners;
    }
}
