<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging\Event;

trait DomainEventBehavior
{
    /**
     * Return some unique string representing the event type with the format:
     *    company.service.version.event.entity.event
     * example:
     *    acme.blog.1.event.post.published
     */
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
        $this->setId($id);
        $this->setOcurredOn($occurredOn);
    }

    /**
     * A universal unique identifier string, the way to go is using UUID version 4.
     */
    public function id(): string
    {
        return (string) $this->headers['_event_id'];
    }

    /**
     * The datetime string representation with DateTime::ATOM format.
     */
    public function occuredOn(): string
    {
        return (string) $this->headers['_event_occurred_on'];
    }

    protected function setId(string $id): void
    {
        $this->headers['_event_id'] = $id;
    }

    protected function setOcurredOn(string $ocurredOn): void
    {
        $this->headers['__event_occurred_on'] = $ocurredOn;
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
     *
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
