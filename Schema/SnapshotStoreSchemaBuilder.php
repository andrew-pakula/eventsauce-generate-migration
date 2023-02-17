<?php

declare(strict_types=1);

namespace Andreo\EventSauce\Doctrine\Migration\Schema;

use Andreo\EventSauce\Snapshotting\Doctrine\Table\DefaultSnapshotTableSchema;
use Andreo\EventSauce\Snapshotting\Doctrine\Table\SnapshotTableSchema;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

final readonly class SnapshotStoreSchemaBuilder implements EventSauceSchemaBuilder
{
    public function __construct(
        private SnapshotTableSchema $tableSchema = new DefaultSnapshotTableSchema(),
        private Schema $schema = new Schema()
    ) {
    }

    public function buildSchema(SchemaMetaDataProvider $schemaMetaDataProvider): Schema
    {
        $table = $this->schema->createTable($schemaMetaDataProvider->getTableName());
        $uuidType = $schemaMetaDataProvider->getUuidType();
        $uuidLength = $schemaMetaDataProvider->getUuidLength();

        $table->addColumn($this->tableSchema->incrementalIdColumn(), Types::INTEGER, [
            'length' => 20,
            'unsigned' => true,
            'autoincrement' => true,
        ]);
        $table->addColumn($this->tableSchema->aggregateRootIdColumn(), $uuidType, [
            'length' => $uuidLength,
            'fixed' => true,
        ]);
        $table->addColumn($this->tableSchema->versionColumn(), Types::INTEGER, [
            'length' => 20,
            'unsigned' => true,
        ]);
        $table->addColumn($this->tableSchema->payloadColumn(), Types::STRING, [
            'length' => 16001,
        ]);
        $table->setPrimaryKey([$this->tableSchema->incrementalIdColumn()]);
        $table->addIndex(
            [$this->tableSchema->aggregateRootIdColumn(), $this->tableSchema->versionColumn()],
            'last_snapshot'
        );
        $table->addOption('charset', $schemaMetaDataProvider->getCharset());
        $table->addOption('collation', $schemaMetaDataProvider->getCollation());

        return $this->schema;
    }
}
