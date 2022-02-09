<?php

declare(strict_types=1);

namespace Andreo\EventSauce\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

final class DefaultOutboxMessageSchemaBuilder implements OutboxMessageSchemaBuilder
{
    public function __construct(private Schema $schema = new Schema())
    {
    }

    public function build(string $name): Schema
    {
        $table = $this->schema->createTable($name);

        $table->addColumn('id', Types::BIGINT, [
            'length' => 20,
            'unsigned' => true,
            'autoincrement' => true,
        ]);
        $table->addColumn('consumed', Types::BOOLEAN, [
            'unsigned' => true,
            'default' => 0,
        ]);
        $table->addColumn('payload', Types::STRING, [
            'length' => 16001,
        ]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['consumed', 'id'], 'is_consumed');
        $table->addOption('charset', 'utf8mb4');
        $table->addOption('collation', 'utf8mb4_general_ci');

        return $this->schema;
    }
}
