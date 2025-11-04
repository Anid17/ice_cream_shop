<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseDao.php';

class UsersDao extends BaseDao {
    public function __construct(PDO $pdo) { parent::__construct($pdo, 'users'); }

    public function create(array $data): int {
        $sql = "INSERT INTO users (name, email, password_hash, role_id)
                VALUES (:name, :email, :password_hash, :role_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password_hash' => $data['password_hash'],
            ':role_id' => (int)$data['role_id'],
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        $params = [':id'=>$id];
        foreach (['name','email','password_hash','role_id'] as $col) {
            if (array_key_exists($col, $data)) {
                $fields[] = "$col = :$col";
                $params[":$col"] = ($col==='role_id') ? (int)$data[$col] : $data[$col];
            }
        }
        if (!$fields) return false;
        $sql = "UPDATE users SET ".implode(', ',$fields)." WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
