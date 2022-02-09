<?php

declare(strict_types=1);

namespace Andreo\EventSauce\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;

interface EventMessageSchemaBuilder
{
    public function build(string $name, string $uuidType): Schema;
}
