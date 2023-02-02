## eventsauce-migration-generator 3.0

Command to generate doctrine migrations per aggregate 

[About table schema](https://eventsauce.io/docs/message-storage/repository-table-schema/)

### Installation

```bash
composer require andreo/eventsauce-migration-generator
```

#### Previous versions doc

- [2.0](https://github.com/eventsauce-symfony/eventsauce-migration-generator/tree/2.0.2)

### Requirements

- PHP >=8.2
- Symfony console ^6.0


### Config doctrine migrations

In the first step, configure the [doctrine migrations](https://www.doctrine-project.org/projects/doctrine-migrations/en/3.3/reference/configuration.html#configuration) package

### Usage

```php

use Andreo\EventSauce\Doctrine\Migration\Command\GenerateDoctrineMigrationForEventSauceCommand;

new GenerateDoctrineMigrationForEventSauceCommand(
    dependencyFactory: $dependencyFactory, // instance of Doctrine\Migrations\DependencyFactory
);
```

### Change table name suffix

```php

use Andreo\EventSauce\Doctrine\Migration\Command\GenerateDoctrineMigrationForEventSauceCommand;
use Andreo\EventSauce\Doctrine\Migration\Schema\TableNameSuffix;

new GenerateDoctrineMigrationForEventSauceCommand(
    dependencyFactory: $dependencyFactory,
    tableNameSuffix: new TableNameSuffix(event: 'message_store')
);
```

### Generate command

```bash
andreo:eventsauce:doctrine-migrations:generate
```

#### Command options

**aggregate name**

- required
- string

Generate migration for **foo** aggregate

```bash
php bin/console andreo:eventsauce:doctrine-migrations:generate foo
```

**--schema=all**

- optional
- string[]
- available values: event, outbox, snapshot, all
- default value: all

Generate migration for given schemas

```bash
php bin/console andreo:eventsauce:doctrine-migrations:generate foo --schema=event --schema=snapshot
```

**--uuid-type=binary**

- optional
- one of: binary, string
- default value: binary
