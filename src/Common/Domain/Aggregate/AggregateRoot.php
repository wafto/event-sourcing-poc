<?php

declare(strict_types=1);

namespace App\Common\Domain\Aggregate;

use App\Common\Domain\Messaging\Event\DomainEvent;
use App\Common\Domain\Messaging\Event\EventStream;

interface AggregateRoot
{
    public static function reconstitute(EventStream $stream): AggregateRoot;

    public function aggregateRootId(): AggregateRootId;

    public function aggregateRootVersion(): int;

    public function recordThat(DomainEvent $event): void;

    /**
     * @return array<DomainEvent>
     */
    public function pullDomainEvents(): array;

    public function apply(DomainEvent $event): void;
}
