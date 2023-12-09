<?php
declare(strict_types=1);

namespace App\Repositories\Impl;

use App\Core\Repository;
use App\Models\Category;
use App\Models\User;
use App\Repositories\CategoryRepository;

class CategoryRepositoryImpl extends Repository implements CategoryRepository
{

    public function create(Category $category): Category
    {
        $name = $category->getName();
        $userId = $category->getUser()->getId();

        $stmt = $this->connection->prepare("INSERT INTO category(name, user_id) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $userId);
        $stmt->execute();

        $category->setId($this->connection->insert_id);
        return $category;
    }

    public function deleteById(int $id): void
    {
        $stmt = $this->connection->prepare("DELETE FROM category WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public function findByName(string $name): ?Category
    {
        $stmt = $this->connection->prepare("SELECT * FROM category WHERE name = ?");
        $stmt->bind_param("s", $name);

        return $this->find($stmt);
    }

    public function findById(int $id): ?Category
    {
        $stmt = $this->connection->prepare("SELECT * FROM category WHERE id = ?");
        $stmt->bind_param("i", $id);

        return $this->find($stmt);
    }

    private function find($stmt): ?Category
    {
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        if($result === null) return null;

        return new Category($result['id'], $result['name']);
    }

    public function update(Category $category): Category
    {
        $id = $category->getId();
        $name = $category->getName();

        $stmt = $this->connection->prepare("UPDATE category SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();

        return $category;
    }

    public function getAllByUser(User $user): array
    {
        $categories = [];
        $userId = $user->getId();

        $stmt = $this->connection->prepare("SELECT * FROM category WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        foreach ($result as $category) {
            $categories[] = new Category($category['id'], $category['name']);
        }

        return $categories;
    }
}