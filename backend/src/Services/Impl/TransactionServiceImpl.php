<?php
declare(strict_types=1);

namespace App\Services\Impl;

use App\Exceptions\CategoryNotFoundException;
use App\Exceptions\NotFoundException;
use App\Exceptions\UserNotFound;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use App\Services\CategoryService;
use App\Services\TransactionService;
use App\Services\UserService;

class TransactionServiceImpl implements TransactionService
{
    private TransactionRepository $transactionRepository;

    private UserService $userService;
    private CategoryService $categoryService;

    public function __construct(TransactionRepository $transactionRepository, UserService $userService, CategoryService $categoryService)
    {
        $this->transactionRepository = $transactionRepository;
        $this->userService = $userService;
        $this->categoryService = $categoryService;
    }

    /**
     * @throws UserNotFound
     * @throws CategoryNotFoundException
     */
    public function create(Transaction $transaction, int $userId, int $categoryId): Transaction
    {
        $user = $this->userService->findById($userId);
        $category = $this->categoryService->findById($categoryId);

        $transaction->setUser($user);
        $transaction->setCategory($category);
        $transaction->setCreatedAt(new \DateTime());

        return $this->transactionRepository->create($transaction);
    }

    /**
     * @throws UserNotFound
     */
    public function getAllByUser(int $userId): array
    {
        $user = $this->userService->findById($userId);
        return $this->transactionRepository->getAllByUser($user);
    }

    /**
     * @throws UserNotFound
     * @throws CategoryNotFoundException
     */
    public function getAllByUserAndCategory(int $userId, int $categoryId): array
    {
        $user = $this->userService->findById($userId);
        $category = $this->categoryService->findById($categoryId);
        return $this->transactionRepository->getAllByUserAndCategory($user, $category);
    }

    /**
     * @throws NotFoundException
     */
    public function findById(int $id): Transaction
    {
        $transaction = $this->transactionRepository->findById($id);

        if($transaction === null) {
            throw new NotFoundException("Transaction not found");
        }

        return $transaction;
    }

    public function deleteById(int $id): void
    {
        $this->transactionRepository->deleteById($id);
    }

    /**
     * @throws NotFoundException
     */
    public function update(Transaction $transaction): Transaction
    {
        if($this->transactionRepository->findById($transaction->getId()) === null) {
            throw new NotFoundException("Transaction not found");
        }
        return $this->transactionRepository->update($transaction);
    }

    /**
     * @throws UserNotFound
     */
    public function getTransactionsSumByCategory(int $userId): array
    {
        $user = $this->userService->findById($userId);
        return $this->transactionRepository->getTransactionsSumByCategory($user);
    }
}