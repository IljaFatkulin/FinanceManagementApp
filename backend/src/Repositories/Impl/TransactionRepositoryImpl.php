<?php
declare(strict_types=1);

namespace App\Repositories\Impl;

use App\Core\Repository;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;
use App\Repositories\TransactionRepository;

class TransactionRepositoryImpl extends Repository implements TransactionRepository
{

    public function create(Transaction $transaction): Transaction
    {
        $userId = $transaction->getUser()->getId();
        $categoryId = $transaction->getCategory()->getId();
        $type = $transaction->getType()->name;
        $amount = $transaction->getAmount();
        $description = $transaction->getDescription();
        $transactionDate = $transaction->getTransactionDate()->format("Y-m-d H:i:s");
        $createdAt = $transaction->getCreatedAt()->format("Y-m-d H:i:s");

        $stmt = $this->connection->prepare("INSERT INTO transaction(user_id, category_id, type, amount, description, transaction_date, created_at) VALUES(?,?,?,?,?,?,?)");
        $stmt->bind_param("iisdsss", $userId, $categoryId, $type, $amount, $description, $transactionDate, $createdAt);
        $stmt->execute();

        $transaction->setId($this->connection->insert_id);
        return $transaction;
    }

    public function getAllByUser(User $user): array
    {
        $userId = $user->getId();

        $stmt = $this->connection->prepare("SELECT t.id as transaction_id, t.type, t.amount, t.description, t.transaction_date, t.created_at, c.id as category_id, c.name as category_name 
                                            FROM transaction AS t 
                                            INNER JOIN category AS c
                                            ON t.category_id = c.id
                                            WHERE t.user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $transactions = [];
        $user = new User($userId, $user->getEmail());
        foreach ($result as $transaction) {
            $category = new Category(intval($transaction['category_id']), $transaction['category_name']);
            $transactionType = TransactionType::from($transaction['type']);
            $transactions[] = new Transaction(
                intval($transaction['transaction_id']), $user, $category, $transactionType, floatval($transaction['amount']), $transaction['description'], new \DateTime($transaction['transaction_date']), new \DateTime($transaction['created_at'])
            );
        }
        return $transactions;
    }

    public function getAllByUserAndCategory(User $user, Category $category): array
    {
        $userId = $user->getId();
        $categoryId = $category->getId();

        $stmt = $this->connection->prepare("SELECT id, type, amount, description, transaction_date, created_at 
                                            FROM transaction
                                            WHERE user_id = ? AND category_id = ?");
        $stmt->bind_param("ii", $userId, $categoryId);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $transactions = [];
        $user = new User($userId, $user->getEmail());
        foreach ($result as $transaction) {
            $transactionType = TransactionType::from($transaction['type']);
            $transactions[] = new Transaction(
                intval($transaction['id']), $user, $category, $transactionType, floatval($transaction['amount']), $transaction['description'], new \DateTime($transaction['transaction_date']), new \DateTime($transaction['created_at'])
            );
        }
        return $transactions;
    }

    /**
     * @throws \Exception
     */
    public function findById(int $id): ?Transaction
    {
        $stmt = $this->connection->prepare("SELECT t.id as transaction_id, u.id as user_id, u.email, c.id as category_id, c.name as category_name, t.type, t.amount, t.description, t.transaction_date, t.created_at
                                            FROM transaction as t
                                            INNER JOIN user as u
                                            ON t.user_id = u.id
                                            INNER JOIN category as c 
                                            ON t.category_id = c.id
                                            WHERE t.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        if($result === null) return null;

        $user = new User(intval($result['user_id']), $result['email']);
        $category = new Category(intval($result['category_id']), $result['category_name']);
        $transactionType = TransactionType::from($result['type']);
        return new Transaction(intval($result['transaction_id']), $user, $category, $transactionType, floatval($result['amount']), $result['description'],
            new \DateTime($result['transaction_date']), new \DateTime($result['created_at']));
    }

    public function deleteById(int $id): void
    {
        $stmt = $this->connection->prepare("DELETE FROM transaction WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public function update(Transaction $transaction): Transaction
    {
        $type = $transaction->getType()->name;
        $amount = $transaction->getAmount();
        $description = $transaction->getDescription();
        $transactionDate = $transaction->getTransactionDate()->format("Y-m-d H:i:s");
        $id = $transaction->getId();

        $stmt = $this->connection->prepare("UPDATE transaction SET type = ?, amount = ?, description = ?, transaction_date = ? WHERE id = ?");
        $stmt->bind_param("sdssi", $type, $amount, $description, $transactionDate, $id);
        $stmt->execute();

        return $transaction;
    }

    public function getTransactionsSumByCategory(User $user): array
    {
        $userId = $user->getId();

        $stmt = $this->connection->prepare("SELECT c.id as category_id, c.name as category_name, t.type, SUM(t.amount) as total_amount
                                            FROM transaction AS t 
                                            INNER JOIN category AS c
                                            ON t.category_id = c.id
                                            WHERE t.user_id = ?
                                            GROUP BY c.id, t.type");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        return $result;
    }
}