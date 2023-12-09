<?php
declare(strict_types=1);

namespace App\Models;

class Transaction implements \JsonSerializable
{
    private ?int $id;
    private ?User $user;
    private ?Category $category;
    private ?TransactionType $type;
    private ?float $amount;
    private ?string $description;
    private ?\DateTime $transactionDate;
    private ?\DateTime $createdAt;

    public function __construct(int $id = null, User $user = null, Category $category = null, TransactionType $type = null, float $amount = null, string $description = null, \DateTime $transactionDate = null, \DateTime $createdAt = null)
    {
        $this->id = $id;
        $this->user = $user;
        $this->category = $category;
        $this->type = $type;
        $this->amount = $amount;
        $this->description = $description;
        $this->transactionDate = $transactionDate;
        $this->createdAt = $createdAt;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    /**
     * @return TransactionType|null
     */
    public function getType(): ?TransactionType
    {
        return $this->type;
    }

    /**
     * @param TransactionType|null $type
     */
    public function setType(?TransactionType $type): void
    {
        $this->type = $type;
    }

    /**
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * @param float|null $amount
     */
    public function setAmount(?float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return \DateTime|null
     */
    public function getTransactionDate(): ?\DateTime
    {
        return $this->transactionDate;
    }

    /**
     * @param \DateTime|null $transactionDate
     */
    public function setTransactionDate(?\DateTime $transactionDate): void
    {
        $this->transactionDate = $transactionDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime|null $createdAt
     */
    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


    public function jsonSerialize(): mixed
    {
        return [
            "id" => $this->id,
            "user" => [
                "user_id" => $this->user?->getId(),
                "email" => $this->user?->getEmail()
            ],
            "category" => $this->category,
            "type" => $this->type,
            "amount" => $this->amount,
            "description" => $this->description,
            "transaction_date" => $this->transactionDate,
            "created_at" => $this->createdAt
        ];
    }
}