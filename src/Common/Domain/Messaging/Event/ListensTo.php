<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging\Event;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class ListensTo
{
    /**
     * @param class-string<DomainEvent> $event
     */
    public function __construct(
        private string $event
    ) {
    }

    /**
     * @return class-string<DomainEvent>
     */
    public function event(): string
    {
        return $this->event;
    }
}
