<?php

declare(strict_types=1);


namespace Andreo\EventSauce\Doctrine\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use EventSauce\MessageRepository\TableSchema\DefaultTableSchema;
use EventSauce\MessageRepository\TableSchema\TableSchema;

final class EventMessageSchemaBuilder
{
    public function __construct(
        private TableSchema $tableSchema = new DefaultTableSchema(),
        private Schema $schema = new Schema()
    )
    {}

    public function build(string $name, string $uuidType): Schema
    {
        $table = $this->schema->createTable($name);

        $table->addColumn($this->tableSchema->eventIdColumn(), $uuidType, [
            'length' => Types::BINARY === $uuidType ? 16: 36,
            'fixed' => true,
        ]);
        $table->addColumn($this->tableSchema->aggregateRootIdColumn(), $uuidType, [
            'length' => Types::BINARY === $uuidType ? 16: 36,
            'fixed' => true,
        ]);
        $table->addColumn($this->tableSchema->versionColumn(), Types::INTEGER, [
            'length' => 20,
            'unsigned' => true,
            'notnull' => false,
        ]);
        $table->addColumn($this->tableSchema->payloadColumn(), Types::STRING, [
            'length' => 16001,
        ]);
        $table->setPrimaryKey([$this->tableSchema->eventIdColumn()]);
        $table->addIndex([$this->tableSchema->aggregateRootIdColumn()], 'aggregate_root_id');
        $table->addIndex(
            [$this->tableSchema->aggregateRootIdColumn(), $this->tableSchema->versionColumn()],
            'reconstitution'
        );
        $table->addOption('charset', 'utf8mb4');
        $table->addOption('collation', 'utf8mb4_general_ci');

        return $this->schema;
    }
}