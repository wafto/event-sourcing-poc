<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging\Event;

use Exception;
use App\Common\Domain\DomainError;

final class DomainEventException extends Exception implements DomainError
{
    public static function unmatchingTypes(string $expected, string $current): DomainEventException
    {
        return new static(
            message: sprintf('Type %s doesn\'t match type %s', $expected, $current),
            code: 1001
        );
    }
}
