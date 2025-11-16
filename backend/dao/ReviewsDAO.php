<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseDao.php';

class ReviewsDao extends BaseDao {
    public function __construct(PDO $pdo) {
        parent::__construct($pdo, 'reviews');
    }

    public function create(array $data): int {
        $sql = "INSERT INTO reviews (user_id, product_id, order_id, rating, comment)
                VALUES (:user_id, :product_id, :order_id, :rating, :comment)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':user_id'    => (int)$data['user_id'],
            ':product_id' => (int)$data['product_id'],
            ':order_id'   => isset($data['order_id']) ? (int)$data['order_id'] : null,
            ':rating'     => (int)$data['rating'],
            ':comment'    => $data['comment'] ?? null,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        $params = [':id' => $id];

        foreach (['user_id', 'product_id', 'order_id', 'rating', 'comment'] as $col) {
            if (array_key_exists($col, $data)) {
                $fields[] = "$col = :$col";
                $val      = $data[$col];

                if (in_array($col, ['user_id', 'product_id', 'order_id', 'rating'], true)) {
                    $val = (int)$val;
                }

                $params[":$col"] = $val;
            }
        }

        if (!$fields) return false;

        $sql  = "UPDATE reviews SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
