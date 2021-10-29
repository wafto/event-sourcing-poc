<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging\Event;

use Generator;

interface EventStream
{
    /**
     * @return string
     */
    public function aggregateRootId(): string;

    /**
     * @return Generator<DomainEvent>
     */
    public function events(): Generator;
}
