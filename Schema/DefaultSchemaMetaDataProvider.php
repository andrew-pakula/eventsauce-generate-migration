<?php

declare(strict_types=1);

namespace Andreo\EventSauce\Doctrine\Migration\Schema;

use Doctrine\DBAL\Types\Types;

final readonly class DefaultSchemaMetaDataProvider implements SchemaMetaDataProvider
{
    private function __construct(
        private string $tableName,
        private string $uuidType,
        private string $charset,
        private string $collation
    ) {
    }

    public static function create(
        string $tableName,
        string $uuidType = Types::BINARY,
        string $charset = 'utf8mb4',
        string $collation = 'utf8mb4_general_ci'
    ): self {
        return new self($tableName, $uuidType, $charset, $collation);
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getUuidType(): string
    {
        return $this->uuidType;
    }

    public function getUuidLength(): int
    {
        return Types::BINARY === $this->getUuidType() ? 16 : 36;
    }

    public function getCharset(): string
    {
        return $this->charset;
    }

    public function getCollation(): string
    {
        return $this->collation;
    }
}
