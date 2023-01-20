<?php

declare(strict_types=1);

namespace Andreo\EventSauce\Doctrine\Migration\Schema;

use Doctrine\DBAL\Schema\Schema;

interface EventSauceSchemaBuilder
{
    public function buildSchema(SchemaMetaDataProvider $schemaMetaDataProvider): Schema;
}
