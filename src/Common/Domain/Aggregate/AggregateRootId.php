<?php

declare(strict_types=1);

namespace App\Common\Domain\Aggregate;

use Stringable;

final class AggregateRootId implements Stringable
{
    private function __construct(private string $value)
    {
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }
}
