<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging;

interface Message
{
    public function payload(): array;

    public function headers(): array;

    public function withHeader(string $key, string|int|float|null|bool|array $value): Message;
}
