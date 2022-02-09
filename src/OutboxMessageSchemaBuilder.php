<?php

declare(strict_types=1);

namespace Andreo\EventSauce\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;

interface OutboxMessageSchemaBuilder
{
    public function build(string $name): Schema;
}
