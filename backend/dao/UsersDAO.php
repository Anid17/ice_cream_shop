<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseDao.php';

class UsersDao extends BaseDao {
    public function __construct(PDO $pdo) {
        parent::__construct($pdo, 'users');
    }

    public function create(array $data): int {
        $sql = "INSERT INTO users (username, email, password_hash)
                VALUES (:username, :email, :password_hash)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':username'      => $data['username'],
            ':email'         => $data['email'],
            ':password_hash' => $data['password_hash'],
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    
    public function update(int $id, array $data): bool {
        $fields = [];
        $params = [':id' => $id];

        foreach (['username', 'email', 'password_hash'] as $col) {
            if (array_key_exists($col, $data)) {
                $fields[]        = "$col = :$col";
                $params[":$col"] = $data[$col];
            }
        }

        if (!$fields) {
            return false;
        }

        $sql  = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function getByEmail(string $email): ?array {
  $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
  $stmt->execute([':email' => $email]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  return $row ?: null;
}

}