## eventsauce-migration-generator

Command to generate doctrine migrations per aggregate 

[About table schema](https://eventsauce.io/docs/message-storage/repository-table-schema/)

### Installation

```bash
composer require andreo/eventsauce-migration-generator
```

### Requirements

- PHP ^8.1
- Symfony console ^6.0


### Config doctrine migrations

In the first step, configure the [doctrine migrations](https://www.doctrine-project.org/projects/doctrine-migrations/en/3.3/reference/configuration.html#configuration) package

### Usage

```php

use Andreo\EventSauce\Doctrine\Migration\GenerateEventSauceDoctrineMigrationCommand;

new GenerateEventSauceDoctrineMigrationCommand(
    dependencyFactory: $dependencyFactory, // instance of Doctrine\Migrations\DependencyFactory
);
```

### Change table name suffix

```php

use Andreo\EventSauce\Doctrine\Migration\TableNameSuffix;
use Andreo\EventSauce\Doctrine\Migration\GenerateEventSauceDoctrineMigrationCommand;

new GenerateEventSauceDoctrineMigrationCommand(
    dependencyFactory: $dependencyFactory,
    tableNameSuffix: new TableNameSuffix(event: 'message_storage', outbox: 'outbox', snapshot: 'snapshot')
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

example with aggregate name **foo**

```bash
php bin/console andreo:eventsauce:doctrine-migrations:generate foo
```

**--schema=all**

- optional
- string[]
- available values: event, outbox, snapshot, all
- default value: all

example for **event** and **snapshot** schemas

```bash
php bin/console andreo:eventsauce:doctrine-migrations:generate foo --schema=event --schema=snapshot
```

**--uuid-type=binary**

- optional
- one of: binary, string
- default value: binary

### Execute migration

Default doctrine migration command

```bash
php bin/console d:m:m
```
