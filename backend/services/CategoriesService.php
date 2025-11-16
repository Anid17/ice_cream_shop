<?php
declare(strict_types=1);

require_once __DIR__ . '/../dao/CategoriesDAO.php';

class CategoriesService {
    private CategoriesDao $dao;

    public function __construct(PDO $pdo) {
        $this->dao = new CategoriesDao($pdo);
    }

    public function getAll(): array {
        return $this->dao->getAll();
    }

    public function getById(int $id): ?array {
        return $this->dao->getById($id);
    }

    public function create(array $data): array {
        if (empty($data['name'])) {
            throw new InvalidArgumentException('Category name is required');
        }

        $id = $this->dao->create(['name' => $data['name']]);
        return $this->dao->getById($id);
    }

    public function update(int $id, array $data): ?array {
        if (empty($data['name'])) {
            throw new InvalidArgumentException('Category name is required');
        }

        $ok = $this->dao->update($id, ['name' => $data['name']]);
        return $ok ? $this->dao->getById($id) : null;
    }

    public function delete(int $id): bool {
        return $this->dao->delete($id);
    }
}
