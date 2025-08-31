<?php

declare(strict_types=1);

namespace Database;

use PDO;
use PDOException;
use function Config\databaseConfig;
use function Core\monad;

/**
 * Establishes a PDO database connection.
 *
 * @param array $config Database configuration array.
 * @return PDO|null Returns a PDO object on success, or null on failure.
 */
function connect(array $config): ?PDO
{
    $dsn = "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
    try {
        $pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        // In a real application, you would log this error.
        error_log("Database connection failed: " . $e->getMessage());
        return null;
    }
}

/**
 * Gets a singleton instance of the database connection.
 * Uses a Monad to handle potential connection failures.
 *
 * @return object A Monad containing the PDO instance or null.
 */
function getConnection(): object
{
    static $pdo = null;

    if ($pdo === null) {
        $config = databaseConfig();
        $pdo = connect($config);
    }

    return monad($pdo);
}

/**
 * Executes a database query.
 *
 * @param string $sql The SQL query string.
 * @param array $params Optional array of parameters for prepared statement.
 * @return object A Monad containing the PDOStatement or null on failure.
 */
function query(string $sql, array $params = []): object
{
    return getConnection()->bind(function (PDO $pdo) use ($sql, $params) {
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return monad($stmt);
        } catch (PDOException $e) {
            error_log("Database query failed: " . $e->getMessage());
            return monad(null);
        }
    });
}

/**
 * Fetches all rows from a PDOStatement.
 *
 * @param object $stmtMonad A Monad containing the PDOStatement.
 * @return array An array of fetched rows, or an empty array on failure.
 */
function fetchAll(object $stmtMonad): array
{
    return $stmtMonad->map(fn(\PDOStatement $stmt) => $stmt->fetchAll())->get() ?? [];
}

/**
 * Fetches a single row from a PDOStatement.
 *
 * @param object $stmtMonad A Monad containing the PDOStatement.
 * @return array|null An associative array representing the row, or null on failure.
 */
function fetchOne(object $stmtMonad): ?array
{
    return $stmtMonad->map(fn(\PDOStatement $stmt) => $stmt->fetch())->get();
}

/**
 * Gets the last inserted ID.
 *
 * @return int|null The last inserted ID, or null on failure.
 */
function lastInsertId(): ?int
{
    return getConnection()->map(fn(PDO $pdo) => (int)$pdo->lastInsertId())->get();
}
