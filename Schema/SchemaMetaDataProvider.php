<?php

declare(strict_types=1);

namespace Andreo\EventSauce\Doctrine\Migration\Schema;

interface SchemaMetaDataProvider
{
    public function getTableName(): string;

    public function getUuidType(): string;

    public function getUuidLength(): int;

    public function getCharset(): string;

    public function getCollation(): string;
}
