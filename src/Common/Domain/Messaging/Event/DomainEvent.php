<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging\Event;

interface DomainEvent
{
    /**
     * Headers constants.
     */
    public const EVENT_TYPE = '__event_type';
    public const EVENT_ID = '__event_id';
    public const EVENT_VERSION = '__event_version';
    public const EVENT_OCCURED_ON = '__event_occurred_on';
    public const AGGREGATE_ROOT_ID = '__aggregate_root_id';

    /**
     * Return some unique string representing the event type with the format:
     *    company.service.version.event.entity.event
     * example:
     *    acme.blog.1.event.post.published
     * @return string
     */
    public static function type(): string;

    /**
     * A universal unique identifier string, the way to go is using UUID version 4.
     * @return string
     */
    public function id(): string;

    /**
     * The applied numeric value representing the aggregate change.
     * @return int
     */
    public function version(): int;

    /**
     * The datetime string representation with DateTime::ATOM format.
     * @return string
     */
    public function occuredOn(): string;

    /**
     * The related aggregate root id.
     * @return string
     */
    public function aggregateRootId(): string;

    /**
     * The event body.
     * @return array<string, scalar|array<scalar>|array<string, scalar>|null>
     */
    public function payload(): array;

    /**
     * Metadata for the event like, here we store the event id, aggregate id, version, ocurred on.
     * @return array<string, scalar|array<scalar>|array<string, scalar>|null>
     */
    public function headers(): array;

    /**
     * Clone the current event and place or replace with a header and value.
     * @param string $key
     * @param scalar|array<scalar>|array<string, scalar>|null $value
     * @return DomainEvent
     */
    public function withHeader(string $key, string|int|float|null|bool|array $value): DomainEvent;

    /**
     * Return a array representation of the event, this same payload should be used to rebuild it.
     * @return array{headers: array<string, scalar|array<scalar>|array<string, scalar>|null>, payload: array<string, scalar|array<scalar>|array<string, scalar>|null>}
     */
    public function toArray(): array;

    /**
     * Build a new DomainEvent with the specified data returned from toArray method.
     * @template T of scalar
     * @throws DomainEventException
     * @param array{headers: array<string, scalar|array<scalar>|array<string, scalar>|null>, payload: array<string, scalar|array<scalar>|array<string, scalar>|null>} $data
     * @return DomainEvent
     */
    public static function fromArray(array $data): DomainEvent;
}
