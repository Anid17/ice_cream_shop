<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseDao.php';

class ProductsDao extends BaseDao {
    public function __construct(PDO $pdo) { parent::__construct($pdo, 'products'); }

    public function create(array $data): int {
        $sql = "INSERT INTO products (category_id, name, description, price, is_active)
                VALUES (:category_id, :name, :description, :price, :is_active)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':category_id' => (int)$data['category_id'],
            ':name'        => $data['name'],
            ':description' => $data['description'] ?? null,
            ':price'       => (float)$data['price'],
            ':is_active'   => isset($data['is_active']) ? (int)$data['is_active'] : 1,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        $params = [':id'=>$id];
        foreach (['category_id','name','description','price','is_active'] as $col) {
            if (array_key_exists($col, $data)) {
                $fields[] = "$col = :$col";
                $val = $data[$col];
                if ($col==='category_id' || $col==='is_active') $val = (int)$val;
                if ($col==='price') $val = (float)$val;
                $params[":$col"] = $val;
            }
        }
        if (!$fields) return false;
        $sql = "UPDATE products SET ".implode(', ',$fields)." WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
