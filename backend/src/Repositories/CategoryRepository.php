<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Category;
use App\Models\User;

interface CategoryRepository
{
    public function getAllByUser(User $user): array;
    public function create(Category $category): Category;

    public function deleteById(int $id): void;

    public function findByName(string $name): ?Category;
    public function findById(int $id): ?Category;

    public function update(Category $category): Category;
}