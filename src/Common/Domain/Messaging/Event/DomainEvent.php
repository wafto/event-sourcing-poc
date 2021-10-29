<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging\Event;

interface DomainEvent
{
    /**
     * Return some unique string representing the event type with the format:
     *    company.service.version.event.entity.event
     * example:
     *    acme.blog.1.event.post.published
     */
    public static function type(): string;

    /**
     * A universal unique identifier string, the way to go is using UUID version 4.
     */
    public function id(): string;

    /**
     * The datetime string representation with DateTime::ATOM format.
     */
    public function occuredOn(): string;

    /**
     * The event body.
     * @return array<string, scalar|array<scalar>|array<string, scalar>|null>
     */
    public function payload(): array;

    /**
     * Metadata for the event like, here we store the event id, aggregate id, version, ocurred on.
     * @return array<string, scalar|array<scalar>|null>
     */
    public function headers(): array;

    /**
     * Clone the current event and place or replace with a header and value.
     *
     * @param string $key
     * @param scalar|array<scalar>|null $value
     * @return DomainEvent
     */
    public function withHeader(string $key, string|int|float|null|bool|array $value): DomainEvent;
}
