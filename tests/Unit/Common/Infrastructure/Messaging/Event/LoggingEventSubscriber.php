<?php

declare(strict_types=1);

namespace Tests\Unit\Common\Infrastructure\Messaging\Event;

use App\Common\Domain\Messaging\Event\DomainEvent;
use App\Common\Domain\Messaging\Event\EventSubscriber;
use App\Common\Domain\Messaging\Event\ListensTo;

final class LoggingEventSubscriber implements EventSubscriber
{
    /**
     * @var array<string>
     */
    public array $log = [];

    #[ListensTo(event: EventStubA::class)]
    public function onEventStubA(EventStubA $event): void
    {
        $this->log[] = [__FUNCTION__, $event::class];
    }

    #[ListensTo(event: EventStubA::class)]
    #[ListensTo(event: EventStubC::class)]
    public function onEventAonEventC(DomainEvent $event): void
    {
        $this->log[] = [__FUNCTION__, $event::class];
    }
}
