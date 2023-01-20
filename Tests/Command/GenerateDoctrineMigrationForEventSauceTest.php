<?php

declare(strict_types=1);

namespace Andreo\EventSauce\Doctrine\Migration\Tests\Command;

use Andreo\EventSauce\Doctrine\Migration\Command\GenerateDoctrineMigrationForEventSauceCommand;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Generator\ClassNameGenerator;
use Doctrine\Migrations\Generator\Generator;
use Doctrine\Migrations\Generator\SqlGenerator;
use Doctrine\Migrations\Metadata\AvailableMigration;
use Doctrine\Migrations\Metadata\AvailableMigrationsSet;
use Doctrine\Migrations\Metadata\ExecutedMigrationsList;
use Doctrine\Migrations\Metadata\MigrationPlanList;
use Doctrine\Migrations\MigrationsRepository;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\Migrations\Version\AliasResolver;
use Doctrine\Migrations\Version\MigrationPlanCalculator;
use Doctrine\Migrations\Version\MigrationStatusCalculator;
use Doctrine\Migrations\Version\Version;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class GenerateDoctrineMigrationForEventSauceTest extends TestCase
{
    private Application $application;

    private DependencyFactory $dependencyFactory;

    /**
     * @test
     */
    public function should_create_doctrine_migration(): void
    {
        $command = $this->command();
        $code = $command->execute([
            'prefix' => 'foo',
            '--schema' => ['all'],
        ]);

        $this->assertEquals(Command::SUCCESS, $code);
        $migrateCommand = $this->migrateCommand();
        $code = $migrateCommand->execute([]);
        $this->assertEquals(Command::SUCCESS, $code);
    }

    protected function setUp(): void
    {
        $this->application = new Application();
        $connection = $this->createConfiguredMock(Connection::class, [
            'getDatabasePlatform' => $this->createMock(AbstractPlatform::class),
        ]);

        $configArray = new ConfigurationArray([
            'migrations_paths' => [
                'Andreo\EventSauce\Doctrine\Migration\Tests' => __DIR__ . '/dump',
            ],
        ]);
        $dependencyFactoryMock = $this->createConfiguredMock(DependencyFactory::class, [
            'getConfiguration' => $configArray->getConfiguration(),
            'getConnection' => $connection,
            'getMigrationRepository' => $this->createConfiguredMock(MigrationsRepository::class, [
                'getMigrations' => new AvailableMigrationsSet([
                    'foo' => new AvailableMigration(new Version('foo'), $this->createMock(AbstractMigration::class)),
                ]),
            ]),
            'getVersionAliasResolver' => $this->createConfiguredMock(AliasResolver::class, [
                'resolveVersionAlias' => new Version('foo'),
            ]),
            'getMigrationStatusCalculator' => $this->createConfiguredMock(MigrationStatusCalculator::class, [
                'getExecutedUnavailableMigrations' => new ExecutedMigrationsList([]),
            ]),
            'getMigrationPlanCalculator' => $this->createConfiguredMock(MigrationPlanCalculator::class, [
                'getPlanUntilVersion' => new MigrationPlanList([], 'up'),
            ]),
            'getMigrationSqlGenerator' => $this->createConfiguredMock(SqlGenerator::class, [
                'generate' => '',
            ]),
            'getMigrationGenerator' => new Generator($configArray->getConfiguration()),
            'getClassNameGenerator' => new ClassNameGenerator(),
        ]);

        $this->dependencyFactory = $dependencyFactoryMock;
    }

    private function command(): CommandTester
    {
        $command = new GenerateDoctrineMigrationForEventSauceCommand(
            $this->dependencyFactory,
        );
        $this->application->add($command);
        $command = $this->application->find('andreo:eventsauce:doctrine-migrations:generate');

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
