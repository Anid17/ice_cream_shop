<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseDao.php';

class OrdersDao extends BaseDao {
    public function __construct(PDO $pdo) {
        parent::__construct($pdo, 'orders');
    }

    public function create(array $data): int {
        $sql = "INSERT INTO orders (user_id, total)
                VALUES (:user_id, :total)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => (int)$data['user_id'],
            ':total'   => isset($data['total']) ? (float)$data['total'] : null,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        $params = [':id' => $id];

        foreach (['user_id', 'total'] as $col) {
            if (array_key_exists($col, $data)) {
                $fields[] = "$col = :$col";
                $val      = $data[$col];

                if ($col === 'user_id') $val = (int)$val;
                if ($col === 'total')   $val = (float)$val;

                $params[":$col"] = $val;
            }
        }

        if (!$fields) return false;

        $sql  = "UPDATE orders SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
