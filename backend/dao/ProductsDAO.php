<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseDao.php';

class ProductsDao extends BaseDao {
    public function __construct(PDO $pdo) {
        parent::__construct($pdo, 'products');
    }

    public function create(array $data): int {
        $sql = "INSERT INTO products (name, description, price)
                VALUES (:name, :description, :price)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':name'        => $data['name'],
            ':description' => $data['description'] ?? null,
            ':price'       => (float)$data['price'],
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        $params = [':id' => $id];

        foreach (['name', 'description', 'price'] as $col) {
            if (array_key_exists($col, $data)) {
                $fields[] = "$col = :$col";
                $val      = $data[$col];
                if ($col === 'price') {
                    $val = (float)$val;
                }
                $params[":$col"] = $val;
            }
        }

        if (!$fields) return false;

        $sql  = "UPDATE products SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
