<?php

declare(strict_types=1);

namespace Andreo\EventSauce\Doctrine\Migration\Schema;

use Doctrine\DBAL\Types\Types;

final readonly class DefaultSchemaMetaDataProvider implements SchemaMetaDataProvider
{
    private function __construct(
        private string $tableName,
        private string $uuidType
    ) {
    }

    public static function create(
        string $tableName,
        string $uuidType = Types::BINARY
    ): self {
        return new self($tableName, $uuidType);
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
}
