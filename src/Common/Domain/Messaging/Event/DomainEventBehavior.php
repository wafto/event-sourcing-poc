<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging\Event;

trait DomainEventBehavior
{
    abstract public static function type(): string;

    /**
     * @param string $id
     * @param string $occuredOn
     * @param array<string, scalar|array<scalar>|array<string, scalar>|null> $payload
     * @param array<string, scalar|array<scalar>|null> $headers
     */
    public function __construct(
        string $id,
        string $occurredOn,
        protected array $payload,
        protected array $headers = [],
    ) {
        $this->headers['__event_id'] = $id;
        $this->headers['__event_occurred_on'] = $occurredOn;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return (string) $this->headers['__event_id'];
    }

    public function occuredOn(): string
    {
        return (string) $this->headers['__event_occurred_on'];
    }

    /**
     * @return array<string, scalar|array<scalar>|array<string, scalar>|null>
     */
    public function payload(): array
    {
        return $this->payload;
    }

    /**
     * @return array<string, scalar|array<scalar>|null>
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * @param string $key
     * @param scalar|array<scalar>|null $value
     * @return DomainEvent
     */
    public function withHeader(string $key, string|int|float|null|bool|array $value): DomainEvent
    {
        $clone = clone $this;
        $clone->headers[$key] = $value;

        return $clone;
    }
}
