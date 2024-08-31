<?php

namespace App;

/**
 * Class to manage database connection.
 * 
 * This class sets up and provides access to a PDO instance for database operations.
 */
class Database
{
    private \PDO $pdo;

    /**
     * Constructor for the Database class.
     * 
     * @param string $dsn The Data Source Name (DSN) for the database connection.
     * @param string $username The username for the database connection.
     * @param string $password The password for the database connection.
     * @param array<string, mixed> $options Optional array of PDO connection options.
     */
    public function __construct(string $dsn, string $username, string $password, array $options = [])
    {
        $this->pdo = new \PDO($dsn, $username, $password, $options);
    }

    /**
     * Gets the PDO instance for database operations.
     * 
     * @return \PDO The PDO instance.
     */
    public function getPdo(): \PDO
    {
        return $this->pdo;
    }
}