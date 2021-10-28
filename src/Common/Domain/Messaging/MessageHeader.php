<?php

declare(strict_types=1);

namespace App\Common\Domain\Messaging;

final class MessageHeader
{
    /**
     * Unique identifier for a message.
     */
    public const MESSAGE_ID = '__message_id';

    /**
     * Message ocurred datetime.
     */
    public const MESSAGE_OCURRED_ON = '__message_occured_on';

    /**
     * Message related aggregate id.
     */
    public const MESSAGE_AGGREGATE_ID = '__message_aggregate_id';
}
