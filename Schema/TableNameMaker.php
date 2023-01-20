<?php

declare(strict_types=1);

namespace Andreo\EventSauce\Doctrine\Migration\Schema;

final readonly class TableNameMaker
{
    public static function makeTableName(?string $prefix, string $suffix): string
    {
        $suffix = self::toSnakeCase($suffix);
        if (null !== $prefix) {
            $prefix = self::toSnakeCase($prefix);
            $name = sprintf('%s_%s', $prefix, $suffix);
        } else {
            $name = $suffix;
        }

        return $name;
    }

    public static function toSnakeCase(string $name): string
    {
        $replaced = preg_replace('/[A-Z]/', '_\\0', lcfirst($name));
        assert(is_string($replaced));

        return strtolower($replaced);
    }
}
