<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging;

interface Message
{
    /**
     * @return array<string, mixed>
     */
    public function payload(): array;

    /**
     * @return array<string, scalar|array<scalar>|null>
     */
    public function headers(): array;

    /**
     * @param string $key
     * @param scalar|array<scalar>|null $value
     * @return Message
     */
    public function withHeader(string $key, string|int|float|null|bool|array $value): Message;
}
