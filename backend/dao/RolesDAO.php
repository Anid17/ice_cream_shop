<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseDao.php';

class RolesDao extends BaseDao {
    public function __construct(PDO $pdo) { parent::__construct($pdo, 'roles'); }

    public function create(array $data): int {
        $stmt = $this->pdo->prepare("INSERT INTO roles (name) VALUES (:name)");
        $stmt->execute([':name' => $data['name']]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        if (!isset($data['name'])) return false;
        $stmt = $this->pdo->prepare("UPDATE roles SET name = :name WHERE id = :id");
        return $stmt->execute([':name'=>$data['name'], ':id'=>$id]);
    }
}
