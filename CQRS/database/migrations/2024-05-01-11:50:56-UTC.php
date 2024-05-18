<?php

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

return new class
{
		public function up(Schema $schema): void
		{
			 $table = $schema->createTable('book_user');
       $table->addColumn('id', Types::INTEGER, [
         'autoincrement' => true,
         'unsigned'      => true
       ]);
       $table->addColumn('name', Types::STRING, [
         'length' => 255
       ]);
       $table->addColumn('password', Types::STRING, [
         'length' => 255
       ]);
       $table->addColumn('email', Types::STRING, [
         'length' => 255
       ]);
       $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, [
         'default' => 'CURRENT_TIMESTAMP'
       ]);
       $table->addUniqueIndex(['email']);
       $table->setPrimaryKey(['id']);
		}

		public function down(Schema $schema): void
		{
			// Customize your scheme here
		}

};