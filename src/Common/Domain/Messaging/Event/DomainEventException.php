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
}
