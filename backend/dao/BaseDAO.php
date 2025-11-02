<?php
declare(strict_types=1);

abstract class BaseDao {
    protected PDO $pdo;
    protected string $table;
    protected string $pk = 'id';

    public function __construct(PDO $pdo, string $table) {
        $this->pdo = $pdo;
        $this->table = $table;
    }

    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$this->pk} = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getAll(array $filters = [], array $pagination = []): array {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        $where = [];

        if (array_key_exists('is_active', $filters)) {
            $where[] = "is_active = :is_active";
            $params[':is_active'] = (int)$filters['is_active'];
        }

        if ($where) $sql .= " WHERE " . implode(' AND ', $where);
        if (isset($pagination['limit']))  $sql .= " LIMIT :limit";
        if (isset($pagination['offset'])) $sql .= " OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k=>$v) $stmt->bindValue($k, $v);
        if (isset($pagination['limit']))  $stmt->bindValue(':limit', (int)$pagination['limit'], PDO::PARAM_INT);
        if (isset($pagination['offset'])) $stmt->bindValue(':offset',(int)$pagination['offset'],PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE {$this->pk} = :id");
        return $stmt->execute([':id' => $id]);
    }
}
