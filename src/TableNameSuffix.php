<?php

declare(strict_types=1);

namespace Andreo\EventSauce\Doctrine\Migration;

final class TableNameSuffix
{
    public function __construct(
        public readonly string $event = 'event_store',
        public readonly string $outbox = 'outbox',
        public readonly string $snapshot = 'snapshot'
    ) {
    }
}
