<?php

namespace Core;

use PDO;

class Database
{
    protected $connection;

    /** @var \PDOStatement|null */
    protected $statement;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function query(string $query, array $params = [])
    {
        $this->statement = $this->connection->prepare($query);
        $this->statement->execute($params);
        return $this;
    }

    public function get(): array
    {
        $result = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function find()
    {
        $result = $this->statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}
