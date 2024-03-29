<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

final class Migration20240113
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function migrate(): void
    {
        $schema = new Schema();
        $this->createUsersTable($schema);

        $queries = $schema->toSql($this->connection->getDatabasePlatform());

        foreach($queries as $query)
        {
            $this->connection->executeQuery($query);
        }
    }

    public function createUsersTable(Schema $schema): void
    {
        $table = $schema->createTable('users');
        $table->addColumn('id', Type::GUID);
        $table->addColumn('nickname', Type::STRING);
        $table->addColumn('password_hash', Type::STRING);
        $table->addColumn('creation_date', Type::DATETIME);
        $table->addColumn('failed_login_attempts', Type::INTEGER, [
            'default' => 0,
        ]);
        $table->addColumn('last_failed_login_attempt', Type::DATETIME, [
            'notnull' => false,
        ]);
    }

}