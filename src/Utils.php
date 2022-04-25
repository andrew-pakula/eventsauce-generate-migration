<?php

declare(strict_types=1);

namespace Andreo\EventSauce\Doctrine\Migration;

final class Utils
{
    public static function makeTableName(string $prefix, string $suffix): string
    {
        $name = sprintf('%s_%s', $prefix, $suffix);

        return self::toSnakeCase($name);
    }

    public static function toSnakeCase(string $name): string
    {
        $replaced = preg_replace('/[A-Z]/', '_\\0', lcfirst($name));
        assert(is_string($replaced));

        return strtolower($replaced);
    }
}
