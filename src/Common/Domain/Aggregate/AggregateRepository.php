<?php

declare(strict_types=1);

namespace App\Common\Domain\Aggregate;

interface AggregateRepository
{
    public function save(AggregateRoot $aggregate): void;

    public function find(AggregateRootId $aggregateRootId): ?AggregateRoot;
}
