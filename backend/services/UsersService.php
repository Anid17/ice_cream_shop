<?php
declare(strict_types=1);

require_once __DIR__ . '/../dao/UsersDao.php';

class UsersService {
    private UsersDao $dao;

    public function __construct(PDO $pdo) {
        $this->dao = new UsersDao($pdo);
    }

    public function getAll(): array {
        return $this->dao->getAll();
    }

    public function getById(int $id): ?array {
        return $this->dao->getById($id);
    }

    public function create(array $data): array {
        // Basic validacija
        if (empty($data['username'])) {
            throw new InvalidArgumentException('Username is required');
        }
        if (empty($data['email'])) {
            throw new InvalidArgumentException('Email is required');
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }
        if (empty($data['password'])) {
            throw new InvalidArgumentException('Password is required');
        }

      
        $payload = [
            'username'      => $data['username'],
            'email'         => $data['email'],
            'password_hash' => $data['password'],
        ];

        $id = $this->dao->create($payload);
        return $this->dao->getById($id);
    }

    public function update(int $id, array $data): ?array {
        // Validation only if exists
        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }

        // Mapiraj u kolone iz baze
        $updateData = [];
        foreach (['username', 'email', 'password'] as $key) {
            if (isset($data[$key])) {
                if ($key === 'password') {
                    $updateData['password_hash'] = $data['password'];
                } else {
                    $updateData[$key] = $data[$key];
                }
            }
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
