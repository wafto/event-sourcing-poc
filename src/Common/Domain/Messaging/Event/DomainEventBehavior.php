<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging\Event;

trait DomainEventBehavior
{
    /**
     * @var array<string, scalar|array<scalar>|array<string, scalar>|null> $headers
     */
    protected array $headers = [];

    /**
     * @var array<string, scalar|array<scalar>|array<string, scalar>|null> $payload
     */
    protected array $payload = [];

    /**
     * @param string $aggregateRootId
     * @param array<string, scalar|array<scalar>|array<string, scalar>|null> $payload
     * @param array<string, scalar|array<scalar>|array<string, scalar>|null> $headers
     */
    public function __construct(
        string $aggregateRootId,
        array $payload,
        array $headers = [],
    ) {
        $this->payload = $payload;

        $this->headers = array_merge($headers, [
            DomainEvent::EVENT_TYPE => static::type(),
            DomainEvent::EVENT_ID => $headers[DomainEvent::EVENT_ID] ?? null,
            DomainEvent::EVENT_OCCURED_ON => $headers[DomainEvent::EVENT_OCCURED_ON] ?? null,
            DomainEvent::EVENT_VERSION => $headers[DomainEvent::EVENT_VERSION] ?? 1,
            DomainEvent::AGGREGATE_ROOT_ID => $aggregateRootId,
        ]);
    }

    public function id(): ?string
    {
        return null === $this->headers[DomainEvent::EVENT_ID] ? null : strval($this->headers[DomainEvent::EVENT_ID]);
    }

    public function version(): int
    {
        return intval($this->headers[DomainEvent::EVENT_VERSION]);
    }

    public function occuredOn(): ?string
    {
        return null === $this->headers[DomainEvent::EVENT_OCCURED_ON] ? null : strval($this->headers[DomainEvent::EVENT_OCCURED_ON]);
    }

    public function aggregateRootId(): string
    {
        return strval($this->headers[DomainEvent::AGGREGATE_ROOT_ID]);
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function withHeader(string $key, string|int|float|null|bool|array $value): DomainEvent
    {
        $clone = clone $this;
        $clone->headers[$key] = $value;

        return $clone;
    }

    public function toArray(): array
    {
        return [
            'headers' => $this->headers(),
            'payload' => $this->payload(),
        ];
    }

    public static function fromArray(array $data): DomainEvent
    {
        if ($data['headers'][DomainEvent::EVENT_TYPE] != static::type()) {
            throw DomainEventException::unmatchingTypes(strval($data['headers'][DomainEvent::EVENT_TYPE]), static::type());
        }

        return new static(
            aggregateRootId: strval($data['headers'][DomainEvent::AGGREGATE_ROOT_ID]),
            payload: (array) $data['payload'],
            headers: (array) $data['headers'],
        );
    }
}
