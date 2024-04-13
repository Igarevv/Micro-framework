<?php

use Doctrine\DBAL\Schema\Schema;

return new class
{
		public function up(Schema $schema): void
		{
        $table = $schema->createTable('car1');
        $table->addColumn('id', 'integer');
		}

		public function down(Schema $schema): void
		{
			// Customize your scheme here
		}

};