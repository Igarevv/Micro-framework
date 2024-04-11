<?php

namespace Igarevv\Micrame\Console\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

class MigrateCommand implements CommandInterface
{

    private string $name = 'migrate';

    private const MIGRATION_TABLE_NAME = 'migrations';

    public function __construct(
      private Connection $connection
    ) {}

    public function execute(array $params = []): int
    {
        $this->createMigrationTable();
        return 0;
    }

    private function createMigrationTable(): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if ( ! $schemaManager->tableExists(self::MIGRATION_TABLE_NAME)) {
            $schema = new Schema();
            $table = $schema->createTable(self::MIGRATION_TABLE_NAME);
            $table->addColumn('id', Types::INTEGER, [
              'unsigned' => true,
              'autoincrement' => true,
            ]);
            $table->addColumn('migration', Types::STRING, [
              'length' => 255,
            ]);
            $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, [
              'default' => 'CURRENT_TIMESTAMP'
            ]);
            $table->setPrimaryKey(['id']);

            $sql = $schema->toSql($this->connection->getDatabasePlatform());
            $this->connection->executeQuery($sql[0]);
        }
    }

}