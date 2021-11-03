<?php

declare(strict_types=1);

use App\Common\Domain\Aggregate\AggregateRoot;
use App\Common\Domain\Aggregate\AggregateRootBehavior;
use App\Common\Domain\Messaging\Event\DomainEvent;
use App\Common\Domain\Messaging\Event\DomainEventBehavior;

require_once __DIR__ . '/vendor/autoload.php';

final class PersonRenamed implements DomainEvent
{
    use DomainEventBehavior;

    public static function type(): string
    {
        return 'poc.users.1.event.user.renamed';
    }
}

final class Fullname implements Stringable
{
    public function __construct(
        private string $name,
        private string $middle,
        private string $last
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function middle(): string
    {
        return $this->middle;
    }

    public function last(): string
    {
        return $this->last;
    }

    public function __toString(): string
    {
        return sprintf('%s %s %s', $this->name, $this->middle, $this->last);
    }
}

final class Person implements AggregateRoot
{
    use AggregateRootBehavior;

    private ?Fullname $name;

    public function rename(string $name, string $middle, string $last): void
    {
        $this->recordThat(new PersonRenamed(
            $this->aggregateRootId()->value(),
            payload: compact('name', 'middle', 'last')
        ));
    }

    public function applyPersonRenamed(PersonRenamed $event): void
    {
        $this->name = new Fullname(...$event->payload());
    }
}
