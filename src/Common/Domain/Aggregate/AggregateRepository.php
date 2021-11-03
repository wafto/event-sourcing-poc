<?php

declare(strict_types=1);

namespace App\Common\Domain\Aggregate;

interface AggregateRepository
{
    public function persist(AggregateRoot $aggregate): void;

    public function retrieve(AggregateRootId $aggregateRootId): ?AggregateRoot;
}
