<?php
declare(strict_types=1);

require_once __DIR__ . '/../dao/ProductsDao.php';

class ProductsService {
    private ProductsDao $dao;

    public function __construct(PDO $pdo) {
        $this->dao = new ProductsDao($pdo);
    }

    public function getAll(): array {
        return $this->dao->getAll();
    }

    public function getById(int $id): ?array {
        return $this->dao->getById($id);
    }

    public function create(array $data): array {
        if (empty($data['name'])) {
            throw new InvalidArgumentException('Product name is required');
        }
        if (!isset($data['price']) || !is_numeric($data['price']) || (float)$data['price'] <= 0) {
            throw new InvalidArgumentException('Price must be a positive number');
        }

        $payload = [
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'price'       => (float)$data['price'],
        ];

        $id = $this->dao->create($payload);
        return $this->dao->getById($id);
    }

    public function update(int $id, array $data): ?array {
        $updateData = [];

        if (isset($data['name']) && $data['name'] !== '') {
            $updateData['name'] = $data['name'];
        }
        if (array_key_exists('description', $data)) {
            $updateData['description'] = $data['description'];
        }
        if (isset($data['price'])) {
            if (!is_numeric($data['price']) || (float)$data['price'] <= 0) {
                throw new InvalidArgumentException('Price must be a positive number');
            }
            $updateData['price'] = (float)$data['price'];
        }

        if (!$updateData) {
            throw new InvalidArgumentException('No valid fields to update');
        }

        $ok = $this->dao->update($id, $updateData);
        return $ok ? $this->dao->getById($id) : null;
    }

    public function delete(int $id): bool {
        return $this->dao->delete($id);
    }
}
