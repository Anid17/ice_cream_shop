<?php
declare(strict_types=1);

require_once __DIR__ . '/../dao/ReviewsDAO.php';

class ReviewsService {
    private ReviewsDao $dao;

    public function __construct(PDO $pdo) {
        $this->dao = new ReviewsDao($pdo);
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
        if (empty($data['product_id'])) {
            throw new InvalidArgumentException('product_id is required');
        }
        if (!isset($data['rating']) || !is_numeric($data['rating'])) {
            throw new InvalidArgumentException('rating is required');
        }
        $rating = (int)$data['rating'];
        if ($rating < 1 || $rating > 5) {
            throw new InvalidArgumentException('rating must be between 1 and 5');
        }

        $payload = [
            'user_id'    => (int)$data['user_id'],
            'product_id' => (int)$data['product_id'],
            'order_id'   => isset($data['order_id']) ? (int)$data['order_id'] : null,
            'rating'     => $rating,
            'comment'    => $data['comment'] ?? null,
        ];

        $id = $this->dao->create($payload);
        return $this->dao->getById($id);
    }

    public function update(int $id, array $data): ?array {
        $updateData = [];

        if (isset($data['user_id'])) {
            $updateData['user_id'] = (int)$data['user_id'];
        }
        if (isset($data['product_id'])) {
            $updateData['product_id'] = (int)$data['product_id'];
        }
        if (array_key_exists('order_id', $data)) {
            $updateData['order_id'] = $data['order_id'] !== null ? (int)$data['order_id'] : null;
        }
        if (isset($data['rating'])) {
            if (!is_numeric($data['rating'])) {
                throw new InvalidArgumentException('rating must be numeric');
            }
            $rating = (int)$data['rating'];
            if ($rating < 1 || $rating > 5) {
                throw new InvalidArgumentException('rating must be between 1 and 5');
            }
            $updateData['rating'] = $rating;
        }
        if (array_key_exists('comment', $data)) {
            $updateData['comment'] = $data['comment'];
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
