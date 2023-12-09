<?php
declare(strict_types=1);

namespace App\Services\Impl;

use App\Exceptions\CategoryNotFoundException;
use App\Exceptions\DuplicateKeyException;
use App\Models\Category;
use App\Models\User;
use App\Repositories\CategoryRepository;
use App\Services\CategoryService;

class CategoryServiceImpl implements CategoryService
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllByUser(User $user): array
    {
        return $this->categoryRepository->getAllByUser($user);
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function findByName(string $name): Category
    {
        return $this->find($this->categoryRepository->findByName($name));
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function findById(int $id): Category
    {
        return $this->find($this->categoryRepository->findById($id));
    }

    /**
     * @throws CategoryNotFoundException
     */
    private function find(?Category $category): Category
    {
        if($category === null) {
            throw new CategoryNotFoundException();
        }

        return $category;
    }

    /**
     * @throws DuplicateKeyException
     */
    public function create(Category $category, User $user): Category
    {
        $category->setUser($user);
        if($this->categoryRepository->findByName($category->getName())) {
            throw new DuplicateKeyException("Category already exists");
        }

        return $this->categoryRepository->create($category);
    }

    /**
     * @throws CategoryNotFoundException
     * @throws DuplicateKeyException
     */
    public function rename(int $id, string $newName): Category
    {
        $category = $this->findById($id);

        if($this->categoryRepository->findByName($newName)) {
            throw new DuplicateKeyException("Category already exists");
        }

        $category->setName($newName);
        return $this->categoryRepository->update($category);
    }

    public function deleteById(int $id): void
    {
        $this->categoryRepository->deleteById($id);
    }
}