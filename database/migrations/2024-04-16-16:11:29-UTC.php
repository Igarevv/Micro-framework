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
        $table->setPrimaryKey(['id']);

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


        $book_author_genre = $schema->createTable('book_genre_author');
        $book_author_genre->addColumn('id', Types::INTEGER, [
          'autoincrement' => true
        ]);
        $book_author_genre->addColumn('book_id', Types::INTEGER,[
          'unsigned' => true
        ]);
        $book_author_genre->addColumn('author_id', Types::INTEGER,[
          'unsigned' => true
        ]);
        $book_author_genre->addColumn('genre', Types::JSON);
        $book_author_genre->setPrimaryKey(['id']);
        $book_author_genre->addForeignKeyConstraint('book', ['book_id'], ['id'], ['onDelete' => 'CASCADE']);
        $book_author_genre->addForeignKeyConstraint('author', ['author_id'], ['id'], ['onDelete' => 'CASCADE']);
		}

		public function down(Schema $schema): void
		{
			// Customize your scheme here
		}

};