<?php

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

return new class
{
		public function up(Schema $schema): void
		{
        $table = $schema->createTable('book');
        $table->addColumn('id', Types::INTEGER,[
          'unsigned' => true,
          'autoincrement' => true
        ]);
        $table->addColumn('title', Types::STRING, [
          'length' => 255,
            'notnull' => true,
        ]);
        $table->addColumn('year', Types::INTEGER, [
          'notnull' => true,
          'length' => 4
        ]);
        $table->addColumn('description', Types::TEXT);
        $table->addColumn('image_cdn_id', Types::STRING, [
          'length' => 255
        ]);
        $table->addColumn('genre', Types::JSON);
        $table->addColumn('isbn', Types::BIGINT);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['isbn']);

        $author = $schema->createTable('author');
        $author->addColumn('id', Types::INTEGER,[
          'unsigned' => true,
          'autoincrement' => true
        ]);
        $author->addColumn('first_name', Types::STRING, [
          'length' => 255
        ]);
        $author->addColumn('last_name', Types::STRING, [
          'length' => 255
        ]);
        $author->setPrimaryKey(['id']);


        $book_author = $schema->createTable('book_author');
        $book_author->addColumn('id', Types::INTEGER, [
          'autoincrement' => true
        ]);
        $book_author->addColumn('book_id', Types::INTEGER,[
          'unsigned' => true
        ]);
        $book_author->addColumn('author_id', Types::INTEGER,[
          'unsigned' => true
        ]);
        $book_author->addColumn('created_at', Types::DATE_MUTABLE,[
          'default' => 'NOW()'
        ]);
        $book_author->setPrimaryKey(['id']);
        $book_author->addForeignKeyConstraint('book', ['book_id'], ['id'], ['onDelete' => 'CASCADE']);
        $book_author->addForeignKeyConstraint('author', ['author_id'], ['id'], ['onDelete' => 'CASCADE']);

		}

		public function down(Schema $schema): void
		{
			// Customize your scheme here
		}

};