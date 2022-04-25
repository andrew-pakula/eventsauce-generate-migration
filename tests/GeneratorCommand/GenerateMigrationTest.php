<?php

declare(strict_types=1);

namespace Tests\GeneratorCommand;

use Andreo\EventSauce\Doctrine\Migration\GenerateEventSauceDoctrineMigrationCommand;
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
    public function should_create_database_structure_for_given_prefix(): void
    {
        $schemaManager = $this->connection->createSchemaManager();
        if ($schemaManager->tablesExist('foo_event_store')) {
            $this->connection->executeQuery('DROP TABLE IF EXISTS `foo_event_store`');
        }
        if ($schemaManager->tablesExist('foo_outbox')) {
            $this->connection->executeQuery('DROP TABLE IF EXISTS `foo_outbox`');
        }
        if ($schemaManager->tablesExist('foo_snapshot')) {
            $this->connection->executeQuery('DROP TABLE IF EXISTS `foo_snapshot`');
        }

        $command = $this->command();
        $code = $command->execute([
            'prefix' => 'foo',
            '--schema' => ['all'],
        ]);

        $this->assertEquals(0, $code);
        $migrateCommand = $this->migrateCommand();
        $code = $migrateCommand->execute([]);
        $this->assertEquals(0, $code);

        $this->assertTrue($schemaManager->tablesExist('foo_event_store'));
        $this->assertTrue($schemaManager->tablesExist('foo_outbox'));
        $this->assertTrue($schemaManager->tablesExist('foo_snapshot'));
    }

    /**
     * @test
     */
    public function should_create_database_structure_without_prefix(): void
    {
        $schemaManager = $this->connection->createSchemaManager();
        if ($schemaManager->tablesExist('foo_event_store')) {
            $this->connection->executeQuery('DROP TABLE IF EXISTS `foo_event_store`');
        }
        if ($schemaManager->tablesExist('foo_outbox')) {
            $this->connection->executeQuery('DROP TABLE IF EXISTS `foo_outbox`');
        }
        if ($schemaManager->tablesExist('foo_snapshot')) {
            $this->connection->executeQuery('DROP TABLE IF EXISTS `foo_snapshot`');
        }
        if ($schemaManager->tablesExist('event_store')) {
            $this->connection->executeQuery('DROP TABLE IF EXISTS `foo_event_store`');
        }
        if ($schemaManager->tablesExist('outbox')) {
            $this->connection->executeQuery('DROP TABLE IF EXISTS `foo_outbox`');
        }
        if ($schemaManager->tablesExist('snapshot')) {
            $this->connection->executeQuery('DROP TABLE IF EXISTS `foo_snapshot`');
        }

        $command = $this->command();
        $code = $command->execute([
            '--schema' => ['all'],
        ]);

        $this->assertEquals(0, $code);
        $migrateCommand = $this->migrateCommand();
        $code = $migrateCommand->execute([]);
        $this->assertEquals(0, $code);

        $this->assertTrue($schemaManager->tablesExist('event_store'));
        $this->assertTrue($schemaManager->tablesExist('outbox'));
        $this->assertTrue($schemaManager->tablesExist('snapshot'));
    }

    protected function setUp(): void
    {
        $this->application = new Application();

        $this->connection = DriverManager::getConnection([
            'dbname' => 'es_migration_generator',
            'user' => 'root',
            'host' => 'mysql',
            'port' => 3306,
            'driver' => 'pdo_mysql',
        ]);

        $config = new PhpFile(__DIR__ . '/migrations.php');
        $this->dependencyFactory = DependencyFactory::fromConnection($config, new ExistingConnection($this->connection));
    }

    private function command(): CommandTester
    {
        $command = new GenerateEventSauceDoctrineMigrationCommand($this->dependencyFactory);
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
