<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging\Event;

use App\Common\Domain\Messaging\Message;

interface DomainEvent extends Message
{
    public static function name(): string;

    public function id(): string;

    public function occuredOn(): string;
}
