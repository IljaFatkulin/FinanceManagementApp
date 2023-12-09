<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;

interface TransactionRepository
{
    public function create(Transaction $transaction): Transaction;

    public function getAllByUser(User $user): array;
    public function getAllByUserAndCategory(User $user, Category $category): array;

    public function findById(int $id): ?Transaction;

    public function deleteById(int $id): void;

    public function update(Transaction $transaction): Transaction;

    public function getTransactionsSumByCategory(User $user): array;
}