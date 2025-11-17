<?php
declare(strict_types=1);

abstract class BaseDao {
    protected PDO $pdo;
    protected string $table;
    protected string $pk;

    public function __construct(PDO $pdo, string $table, string $pk = 'id') {
        $this->pdo   = $pdo;
        $this->table = $table;
        $this->pk    = $pk;
    }

    public function getById(int $id): ?array {
        $sql  = "SELECT * FROM {$this->table} WHERE {$this->pk} = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row === false ? null : $row;
    }

    public function getAll(array $filters = []): array {
        $sql    = "SELECT * FROM {$this->table}";
        $params = [];

        if (!empty($filters)) {
            $clauses = [];
            foreach ($filters as $col => $val) {
                $clauses[]       = "$col = :$col";
                $params[":$col"] = $val;
            }
            $sql .= " WHERE " . implode(' AND ', $clauses);
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(int $id): bool {
        $sql  = "DELETE FROM {$this->table} WHERE {$this->pk} = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
