<?php

declare(strict_types=1);

namespace Andreo\EventSauce\Doctrine\Migration\Schema;

final readonly class TableNameSuffix
{
    public function __construct(
        public string $eventStore = 'event_store',
        public string $messageOutbox = 'message_outbox',
        public string $snapshotStore = 'snapshot_store'
    ) {
    }
}
