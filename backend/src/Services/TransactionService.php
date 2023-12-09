<?php
declare(strict_types=1);

namespace App\Services;

use App\Exceptions\CategoryNotFoundException;
use App\Exceptions\NotFoundException;
use App\Exceptions\UserNotFound;
use App\Models\Transaction;

interface TransactionService
{
    /**
     * @throws UserNotFound
     * @throws CategoryNotFoundException
     */
    public function create(Transaction $transaction, int $userId, int $categoryId): Transaction;

    /**
     * @throws UserNotFound
     */
    public function getAllByUser(int $userId): array;
    /**
     * @throws UserNotFound
     * @throws CategoryNotFoundException
     */
    public function getAllByUserAndCategory(int $userId, int $categoryId): array;

    /**
     * @throws NotFoundException
     */
    public function findById(int $id): Transaction;

    public function deleteById(int $id): void;

    /**
     * @throws NotFoundException
     */
    public function update(Transaction $transaction): Transaction;

    public function getTransactionsSumByCategory(int $userId): array;
}