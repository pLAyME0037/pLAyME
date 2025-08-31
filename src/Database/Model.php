<?php

declare (strict_types = 1);

namespace Database;

use function Core\map;
use function Core\monad;
use function Core\reduce;

/**
 * Creates a new record in the specified table.
 *
 * @param string $table The table name.
 * @param array $data An associative array of data to insert.
 * @return object A Monad containing the last inserted ID or null on failure.
 */
function create(string $table, array $data): object
{
    $columns      = implode(', ', array_keys($data));
    $placeholders = ':' . implode(', :', array_keys($data));
    $sql          = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

    return query($sql, $data)->bind(fn($stmt) => monad(lastInsertId()));
}

/**
 * Reads records from the specified table.
 *
 * @param string $table The table name.
 * @param array $conditions An associative array of conditions (e.g., ['id' => 1]).
 * @param array $orderBy An associative array for ordering (e.g., ['column' => 'ASC']).
 * @param int|null $limit The maximum number of records to return.
 * @return object A Monad containing an array of records or an empty array on failure.
 */
function read(string $table, array $conditions = [], array $orderBy = [], ?int $limit = null): object
{
    $sql    = "SELECT * FROM {$table}";
    $params = [];

    if (! empty($conditions)) {
        $whereClauses = map(fn($key) => "{$key} = :{$key}", array_keys($conditions));
        $sql .= " WHERE " . implode(' AND ', $whereClauses);
        $params = $conditions;
    }

    if (! empty($orderBy)) {
        $orderByClauses = map(fn($key, $value) => "{$key} {$value}", array_keys($orderBy), $orderBy);
        $sql .= " ORDER BY " . implode(', ', $orderByClauses);
    }

    if ($limit !== null) {
        $sql .= " LIMIT {$limit}";
    }

    return query($sql, $params)->map(fn($stmt) => fetchAll($stmt));
}

/**
 * Updates records in the specified table.
 *
 * @param string $table The table name.
 * @param array $data An associative array of data to update.
 * @param array $conditions An associative array of conditions.
 * @return object A Monad containing the number of affected rows or null on failure.
 */
function update($table, $data, $conditions)
{
    if (empty($data) || empty($conditions)) {
        return monad(null); // No data to update or no conditions specified
    }

    $setClauses   = map(fn($key) => "{$key} = :{$key}", array_keys($data));
    $whereClauses = map(fn($key) => "{$key} = :where_{$key}", array_keys($conditions));

    $sql = "UPDATE {$table} SET " . implode(', ', $setClauses) . " WHERE " . implode(' AND ', $whereClauses);

    $params = array_merge($data, map(fn($key, $value) => ["where_{$key}" => $value], array_keys($conditions), $conditions));

    return query($sql, $params)->map(fn(\PDOStatement $stmt) => $stmt->rowCount());
}

/**
 * Deletes records from the specified table.
 *
 * @param string $table The table name.
 * @param array $conditions An associative array of conditions.
 * @return object A Monad containing the number of affected rows or null on failure.
 */
function delete(string $table, array $conditions): object
{
    if (empty($conditions)) {
        return monad(null); // No conditions specified for deletion
    }

    $whereClauses = map(fn($key) => "{$key} = :{$key}", array_keys($conditions));
    $sql          = "DELETE FROM {$table} WHERE " . implode(' AND ', $whereClauses);

    return query($sql, $conditions)->map(fn(\PDOStatement $stmt) => $stmt->rowCount());
}

/**
 * Finds a single record by its primary key (e.g., 'id').
 *
 * @param string $table The table name.
 * @param mixed $id The primary key value.
 * @param string $primaryKey The name of the primary key column.
 * @return object A Monad containing the record or null if not found.
 */
function find(string $table, mixed $id, string $primaryKey = 'id'): object
{
    return read($table, [$primaryKey => $id], [], 1)->map(fn(array $results) => $results[0] ?? null);
}

/**
 * Finds all records matching specific conditions.
 *
 * @param string $table The table name.
 * @param array $conditions An associative array of conditions.
 * @return object A Monad containing an array of matching records or an empty array.
 */
function findAll(string $table, array $conditions = []): object
{
    return read($table, $conditions);
}
