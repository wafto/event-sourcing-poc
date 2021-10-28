<?php

declare(strict_types=1);

namespace App\Common\Domain;

use DateTimeImmutable;
use DateTimeZone;

interface Clock
{
    public function now(): DateTimeImmutable;

    public function timezone(): DateTimeZone;
}
