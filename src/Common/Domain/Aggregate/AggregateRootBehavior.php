<?php

declare(strict_types=1);

namespace App\Common\Domain\Aggregate;

use App\Common\Domain\Messaging\Event\DomainEvent;
use App\Common\Domain\Messaging\Event\EventStream;

trait AggregateRootBehavior
{
    protected AggregateRootId $aggregateRootId;

    protected int $aggregateRootVersion = 0;

    /**
     * @var array<DomainEvent>
     */
    protected array $recordedDomainEvents = [];

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function aggregateRootVersion(): int
    {
        return $this->aggregateRootVersion;
    }

    public function recordThat(DomainEvent $event): void
    {
        $event = $event->withHeader(
            key: DomainEvent::EVENT_VERSION,
            value: $this->aggregateRootVersion + 1
        );

        $this->apply(event: $event);
        $this->recordedDomainEvents[] = $event;
    }

    /**
     * @return array<DomainEvent>
     */
    public function pullDomainEvents(): array
    {
        $domainEvents = $this->recordedDomainEvents;
        $this->recordedDomainEvents = [];

        return $domainEvents;
    }

    public function apply(DomainEvent $event): void
    {
        $parts = explode('\\', $event::class);
        $method = sprintf('apply%s', ucfirst(end($parts)));

        if (method_exists($this, $method)) {
            $this->{$method}($event);
        }

        $this->aggregateRootVersion = $event->version();
    }
}
