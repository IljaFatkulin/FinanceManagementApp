<?php
declare(strict_types=1);

namespace App\Services;

use App\Exceptions\CategoryNotFoundException;
use App\Exceptions\DuplicateKeyException;
use App\Models\Category;
use App\Models\User;

interface CategoryService
{
    public function getAllByUser(User $user): array;

    // Find methods return Category or throw exception
    /**
     * @throws CategoryNotFoundException
     */
    public function findByName(string $name): Category;
    /**
     * @throws CategoryNotFoundException
     */
    public function findById(int $id): Category;

    /**
     * @throws DuplicateKeyException
     */
    public function create(Category $category, User $user): Category;

    /**
     * @throws CategoryNotFoundException
     * @throws DuplicateKeyException
     */
    public function rename(int $id, string $newName): Category;

    public function deleteById(int $id): void;
}