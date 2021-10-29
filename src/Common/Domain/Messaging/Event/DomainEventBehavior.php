<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging\Event;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Ramsey\Uuid\Uuid;

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
            DomainEvent::EVENT_TYPE => static::type()
        ]);

        $this->setId(
            isset($headers[DomainEvent::EVENT_ID])
                ? strval($headers[DomainEvent::EVENT_ID])
                : Uuid::uuid4()->toString()
        );

        $this->setOccurredOn(
            isset($headers[DomainEvent::EVENT_OCCURED_ON])
                ? strval($headers[DomainEvent::EVENT_OCCURED_ON])
                : (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format(DateTimeInterface::ATOM)
        );

        $this->setVersion(
            isset($headers[DomainEvent::EVENT_VERSION])
                ? intval($headers[DomainEvent::EVENT_VERSION])
                : 1
        );

        $this->setAggregateRootId($aggregateRootId);
    }

    public function id(): string
    {
        return strval($this->headers[DomainEvent::EVENT_ID]);
    }

    protected function setId(string $id): void
    {
        $this->headers[DomainEvent::EVENT_ID] = $id;
    }

    public function version(): int
    {
        return intval($this->headers[DomainEvent::EVENT_VERSION]);
    }

    protected function setVersion(int $version): void
    {
        $this->headers[DomainEvent::EVENT_VERSION] = $version;
    }

    public function occuredOn(): string
    {
        return strval($this->headers[DomainEvent::EVENT_OCCURED_ON]);
    }

    protected function setOccurredOn(string $occurredOn): void
    {
        $this->headers[DomainEvent::EVENT_OCCURED_ON] = $occurredOn;
    }

    public function aggregateRootId(): string
    {
        return strval($this->headers[DomainEvent::AGGREGATE_ROOT_ID]);
    }

    protected function setAggregateRootId(string $aggregateRootId): void
    {
        $this->headers[DomainEvent::AGGREGATE_ROOT_ID] = $aggregateRootId;
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

    protected static function validateType(string $expected, string $current): void
    {
        if ($expected != $current) {
            throw DomainEventException::unmatchingTypes($expected, $current);
        }
    }

    public static function fromArray(array $data): DomainEvent
    {
        static::validateType(strval($data['headers'][DomainEvent::EVENT_TYPE]), static::type());

        return new static(
            aggregateRootId: strval($data['headers'][DomainEvent::AGGREGATE_ROOT_ID]),
            payload: (array) $data['payload'],
            headers: (array) $data['headers'],
        );
    }
}
