<?php
declare(strict_types=1);

require_once __DIR__ . '/../dao/OrdersDao.php';

class OrdersService {
    private OrdersDao $dao;

    public function __construct(PDO $pdo) {
        $this->dao = new OrdersDao($pdo);
    }

    public function getAll(): array {
        return $this->dao->getAll();
    }

    public function getById(int $id): ?array {
        return $this->dao->getById($id);
    }

    public function create(array $data): array {
        if (empty($data['user_id'])) {
            throw new InvalidArgumentException('user_id is required');
        }

        $payload = [
            'user_id' => (int)$data['user_id'],
            'total'   => isset($data['total']) ? (float)$data['total'] : null,
        ];

        $id = $this->dao->create($payload);
        return $this->dao->getById($id);
    }

    public function update(int $id, array $data): ?array {
        $updateData = [];

        if (isset($data['user_id'])) {
            $updateData['user_id'] = (int)$data['user_id'];
        }
        if (isset($data['total'])) {
            if (!is_numeric($data['total'])) {
                throw new InvalidArgumentException('Total must be numeric');
            }
            $updateData['total'] = (float)$data['total'];
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
