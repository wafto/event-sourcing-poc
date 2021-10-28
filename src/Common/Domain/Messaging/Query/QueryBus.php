<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging\Query;

interface QueryBus
{
    public function ask(Query $query): Response;
}
