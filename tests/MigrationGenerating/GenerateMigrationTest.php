<?php

declare(strict_types=1);

namespace Tests\MigrationGenerating;

use Andreo\EventSauce\Doctrine\Migration\GenerateAggregateMigrationCommand;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

final class GenerateMigrationTest extends TestCase
{
    private Application $application;

    private Connection $connection;

    private DependencyFactory $dependencyFactory;

    /**
     * @test
     */
    public function should_create_database_structure_for_given_aggregate_name(): void
    {
        $schemaManager = $this->connection->createSchemaManager();
        if ($schemaManager->tablesExist('foo_event_message')) {
            $this->connection->executeQuery('DROP TABLE IF EXISTS `foo_event_message`');
        }
        if ($schemaManager->tablesExist('foo_outbox_message')) {
            $this->connection->executeQuery('DROP TABLE IF EXISTS `foo_outbox_message`');
        }
        if ($schemaManager->tablesExist('foo_snapshot')) {
            $this->connection->executeQuery('DROP TABLE IF EXISTS `foo_snapshot`');
        }

        $command = $this->command();
        $code = $command->execute([
            'aggregate' => 'foo',
            '--schemas' => ['event', 'outbox', 'snapshot'],
        ]);

        $this->assertEquals(0, $code);
        $migrateCommand = $this->migrateCommand();
        $code = $migrateCommand->execute([]);
        $this->assertEquals(0, $code);

        $this->assertTrue($schemaManager->tablesExist('foo_event_message'));
        $this->assertTrue($schemaManager->tablesExist('foo_outbox_message'));
        $this->assertTrue($schemaManager->tablesExist('foo_snapshot'));
    }

    protected function setUp(): void
    {
        $this->application = new Application();

        $this->connection = DriverManager::getConnection([
            'dbname' => 'eventsauce_migration',
            'user' => 'username',
            'password' => 'pswd',
            'host' => 'mysql',
            'port' => 3306,
            'driver' => 'pdo_mysql',
        ]);

        $config = new PhpFile(__DIR__ . '/migrations.php');
        $this->dependencyFactory = DependencyFactory::fromConnection($config, new ExistingConnection($this->connection));
    }

    private function command(): CommandTester
    {
        $command = new GenerateAggregateMigrationCommand($this->dependencyFactory);
        $this->application->add($command);
        $command = $this->application->find('andreo:event-sauce:doctrine:migration:generate');

        return new CommandTester($command);
    }

    private function migrateCommand(): CommandTester
    {
        $command = new MigrateCommand(
            $this->dependencyFactory,
        );
        $this->application->add($command);

        $command = $this->application->find('migrations:migrate');

        return new CommandTester($command);
    }
}
