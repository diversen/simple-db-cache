<?php 

namespace Cache;

use PDO;

class DB
{
    /**
     * Var holding current stmt
     */
    private $stmt = null;
    /**
     * var holding DB handle
     */
    private $dbh = null;
    /**
     * Set database handle direct
     */
    public function setDbh(PDO $dbh)
    {
        $this->dbh = $dbh;
    }
    /**
     * Create a database handle
     */
    public function __construct(PDO $conn)
    {
        $this->dbh = $conn;
    }
    /**
     * Prepare and execute a string of SQL
     */
    public function prepareExecute(string $sql, array $params = []): bool
    {
        $this->stmt = $this->dbh->prepare($sql);
        return $this->stmt->execute($params);
    }
    /**
     * Prepare and fetch all rows with SQL string and params to be prepared
     */
    public function prepareFetchAll(string $sql, array $params = []) : array
    {
        $this->stmt = $this->dbh->prepare($sql);
        $this->stmt->execute($params);
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Prepare execute, and fetch a single row
     */
    public function prepareFetch(string $sql, array $params = []): array
    {
        $this->stmt = $this->dbh->prepare($sql);
        $this->stmt->execute($params);
        // Fetch returns false when 0 rows. FetchAll always returns an array
        $rows = $this->stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($rows)) {
            return $rows;
        }
        return [];
    }
    /**
     * Prepare, execute, and return number of affected rows
     * Use this with 'Delete', 'Update', 'Insert' if you need the row count.
     */
    public function rowCount(): int
    {
        return $this->stmt->rowCount();
    }
    /**
     * Return error info
     */
    public function errorInfo (): array {
        return $this->dbh->errorInfo();
    }
    /**
     * Returns last insert ID
     */
    public function lastInsertId(string $name = null): string
    {
        return $this->dbh->lastInsertId($name);
    }
    /**
     * Begin transaction
     */
    public function beginTransaction(): bool
    {
        return $this->dbh->beginTransaction();
    }
    /**
     * Rollback transaction
     */
    public function rollback(): bool
    {
        return $this->dbh->rollBack();
    }
    /**
     * Commit transaction
     */
    public function commit(): bool
    {
        return $this->dbh->commit();
    }
}