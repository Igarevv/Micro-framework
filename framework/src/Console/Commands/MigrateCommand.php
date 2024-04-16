<?php

namespace Igarevv\Micrame\Console\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Igarevv\Micrame\Console\Exceptions\MigrationException;

class MigrateCommand implements CommandInterface
{

    private string $type = 'migration';

    private string $action = 'migrate';

    private const MIGRATION_TABLE_NAME = 'migrations';

    public function __construct(
      private Connection $connection,
      private string $migrationPath
    ) {}

    public function execute(array $params = []): int
    {
        $this->createMigrationTable();

        $this->connection->setAutoCommit(false);

        $this->connection->beginTransaction();
        try {
            $appliedMigrations = $this->getAppliedMigrations();

            $migrationFiles = $this->getUnappliedMigrations($appliedMigrations);

            $schema = $this->prepareNewSchema($migrationFiles);

            $this->executeMigration($schema);

            echo "Migration process completed successfully".PHP_EOL;

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();
        }
        finally {
            $this->connection->setAutoCommit(true);
        }
        return 0;
    }

    private function executeMigration(Schema $schema): void
    {
        $arraySql = $schema->toSql($this->connection->getDatabasePlatform());

        foreach ($arraySql as $sql) {
            $this->connection->executeQuery($sql);
        }
    }

    private function prepareNewSchema(array $diffBetweenMigrations): Schema
    {
        if ( ! $diffBetweenMigrations) {
            throw new MigrationException('All migrations is up to date');
        }

        $schema = new Schema();

        foreach ($diffBetweenMigrations as $migration) {
            $migrationClass = require $this->migrationPath."/{$migration}";

            $tablesBeforeMigration = count($schema->getTables());

            $migrationClass->up($schema);

            $tablesAfterMigration = count($schema->getTables());

            if ($tablesBeforeMigration !== $tablesAfterMigration) {
                $this->addMigrationToDb($migration);
            } else {
                echo "Migration {$migration} does not have execution code and will be skipped".PHP_EOL;
            }
        }
        return $schema;
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
              'default' => 'CURRENT_TIMESTAMP',
            ]);
            $table->setPrimaryKey(['id']);

            $sql = $schema->toSql($this->connection->getDatabasePlatform());
            $this->connection->executeQuery($sql[0]);

            echo "Migration table created".PHP_EOL;
        }
    }

    private function getAppliedMigrations(): array|false
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $migrations = $queryBuilder
          ->select('migration')
          ->from(self::MIGRATION_TABLE_NAME)
          ->fetchFirstColumn();

        return $migrations;
    }

    private function getUnappliedMigrations(array $appliedMigrations): array
    {
        $files = scandir($this->migrationPath);

        $filteredFiles = array_values(array_diff($files, ['.', '..']));

        return array_diff($filteredFiles, $appliedMigrations);
    }

    private function addMigrationToDb(string $migration): void
    {
        $builder = $this->connection->createQueryBuilder();

        $builder->insert(self::MIGRATION_TABLE_NAME)
          ->values(['migration' => ':migration'])
          ->setParameter('migration', $migration);

        $builder->executeQuery();
    }

}