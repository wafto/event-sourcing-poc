<?php

declare(strict_types=1);

namespace Tests\Unit\Common\Infrastructure\Messaging\Event;

use App\Common\Domain\Messaging\Event\DomainEvent;
use App\Common\Domain\Messaging\Event\EventStream;
use App\Common\Infrastructure\Messaging\Event\ArrayEventStream;
use PHPUnit\Framework\TestCase;

class InMemoryEventStoreTest extends TestCase
{
    /**
     * @var array<DomainEvent>
     */
    private array $store = [];

    public function persist(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->store[] = $event;
        }
    }

    public function retrieve(string $aggregateRootId, int $afterVersion = 0): EventStream
    {
        $result = [];

        foreach ($this->store as $event) {
            if ($aggregateRootId === $event->aggregateRootId() && $event->version() > $afterVersion) {
                $result[] = $event;
            }
        }

        return new ArrayEventStream($aggregateRootId, $result);
    }
}
