<?php

declare(strict_types=1);


namespace Andreo\EventSauce\Doctrine\Migration;

final class TableNameSuffix
{
    public function __construct(
        public readonly string $event = 'event_message',
        public readonly string $outbox = 'outbox_message',
        public readonly string $snapshot = 'snapshot'
    )
    {}
}