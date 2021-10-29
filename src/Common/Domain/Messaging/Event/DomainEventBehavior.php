<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging\Event;

trait DomainEventBehavior
{
    /**
     * @param string $id
     * @param int $version
     * @param string $occuredOn
     * @param string $aggregateRootId
     * @param array<string, scalar|array<scalar>|array<string, scalar>|null> $payload
     * @param array<string, scalar|array<scalar>|null> $headers
     */
    public function __construct(
        string $id,
        int $version,
        string $occuredOn,
        string $aggregateRootId,
        protected array $payload,
        protected array $headers = [],
    ) {
    }

    /**
     * Return some unique string representing the event type with the format:
     *    company.service.version.event.entity.event
     * example:
     *    acme.blog.1.event.post.published
     * @return string
     */
    abstract public static function type(): string;

    /**
     * A universal unique identifier string, the way to go is using UUID version 4.
     * @return string
     */
    public function id(): string
    {
        return (string) $this->headers[DomainEvent::EVENT_ID];
    }

    /**
     * Set the header event id.
     * @param string $id
     */
    protected function setId(string $id): void
    {
        $this->headers[DomainEvent::EVENT_ID] = $id;
    }

    /**
     * The applied numeric value representing the aggregate change.
     * @return int
     */
    public function version(): int
    {
        return (int) $this->headers[DomainEvent::EVENT_VERSION];
    }

    /**
     * Set the header event version.
     * @param int $version
     */
    protected function setVersion(int $version): void
    {
        $this->headers[DomainEvent::EVENT_VERSION] = $version;
    }

    /**
     * The datetime string representation with DateTime::ATOM format.
     * @return string
     */
    public function occuredOn(): string
    {
        return (string) $this->headers[DomainEvent::EVENT_OCCURED_ON];
    }

    /**
     * Set the header occurred on.
     * @param string $occurredOn
     */
    protected function setOccurredOn(string $occurredOn): void
    {
        $this->headers[DomainEvent::EVENT_OCCURED_ON] = $occurredOn;
    }

    /**
     * The related aggregate root id.
     * @return string
     */
    public function aggregateRootId(): string
    {
        return (string) $this->headers[DomainEvent::AGGREGATE_ROOT_ID];
    }

    /**
     * Set the header aggregate root id.
     * @param string $aggregateId
     */
    protected function setAggregateRootId(string $aggregateRootId): void
    {
        $this->headers[DomainEvent::AGGREGATE_ROOT_ID] = $aggregateRootId;
    }

    /**
     * The event body.
     * @return array<string, scalar|array<scalar>|array<string, scalar>|null>
     */
    public function payload(): array
    {
        return $this->payload;
    }

    /**
     * Metadata for the event like, here we store the event id, aggregate id, version, ocurred on.
     * @return array<string, scalar|array<scalar>|null>
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * Clone the current event and place or replace with a header and value.
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

    /**
     * Return a array representation of the event, this same payload should be used to rebuild it.
     * @template T of scalar
     * @return array<string, T|null|array<T>|array<string, T>>
     */
    public function toArray(): array
    {
        return [
            'type' => static::type(),
            'headers' => $this->headers(),
            'payload' => $this->payload(),
        ];
    }

    /**
     * Build a new DomainEvent with the specified data returned from toArray method.
     * @template T of scalar
     * @throws DomainEventException
     * @param array<string, T|null|array<T>|array<string, T>> $data
     * @return DomainEvent
     */
    public static function fromArray(array $data): DomainEvent
    {
        if ($data['type'] != static::type()) {
            throw DomainEventException::unmatchingTypes(
                expected: static::type(),
                current: (string) $data['type'],
            );
        }

        return new static(
            id: $data['headers'][DomainEvent::EVENT_ID],
            occurredOn: $data['headers'][DomainEvent::EVENT_OCCURED_ON],
            payload: (array) $data['payload'],
            headers: (array) $data['headers'],
        );
    }
}
