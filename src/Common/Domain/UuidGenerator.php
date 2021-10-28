<?php

declare(strict_types=1);

namespace App\Common\Domain;

interface UuidGenerator
{
    public function generate(): string;
}
