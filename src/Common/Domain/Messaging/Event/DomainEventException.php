<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging\Event;

use Exception;
use App\Common\Domain\DomainError;

final class DomainEventException extends Exception implements DomainError
{
    public static function unmatchingTypes(string $expected, string $current): DomainEventException
    {
        return new static(sprintf('Input type %s doesn\'t match type %s from the called Domain Event.', $expected, $current));
    }

    public static function cantPersistOlderVersioned(DomainEvent $event): DomainEventException
    {
        return new static(
            sprintf(
                'Domain event with id: %s can\'t be persisted because the current stream has already used that version number.',
                $event->id(),
            )
        );
    }

    public static function cantPersistWithMissingVersion(DomainEvent $event, int $expectedVersion): DomainEventException
    {
        return new static(
            sprintf(
                'Domain event with id: %s can\'t be persisted because the current stream expect an event with version: %s and it got %s.',
                $event->id(),
                $expectedVersion,
                $event->version(),
            )
        );
    }
}
