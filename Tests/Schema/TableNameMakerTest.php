<?php

declare(strict_types=1);

namespace Schema;

use Andreo\EventSauce\Doctrine\Migration\Schema\TableNameMaker;
use PHPUnit\Framework\TestCase;

final class TableNameMakerTest extends TestCase
{
    /**
     * @test
     * @dataProvider getData
     */
    public function should_create_table_name(?string $prefix, string $suffix, string $expected): void
    {
        $tableName = TableNameMaker::makeTableName($prefix, $suffix);

        $this->assertSame($expected, $tableName);
    }

    public function getData(): array
    {
        return [
            [
                'fooBar',
                'baz',
                'foo_bar_baz',
            ],
            [
                'foo_bar',
                'baz',
                'foo_bar_baz',
            ],
            [
                'Foo_bar',
                'Baz',
                'foo_bar_baz',
            ],
        ];
    }
}
