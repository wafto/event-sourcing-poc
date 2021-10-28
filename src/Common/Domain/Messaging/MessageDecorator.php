<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging;

interface MessageDecorator
{
    public function decorate(Message $message): Message;
}
