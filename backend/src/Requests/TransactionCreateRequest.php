<?php
declare(strict_types=1);

namespace App\Requests;

use App\Attributes\Validation;

class TransactionCreateRequest
{
    #[Validation(required: true, errorMessage: "User id is required")]
    private int $user_id;
    #[Validation(required: true, errorMessage: "Category id is required")]
    private int $category_id;
    #[Validation(required: true, errorMessage: "Type is required")]
    private string $type;
    #[Validation(required: true, errorMessage: "Amount is required")]
    private float $amount;
    #[Validation(required: true, errorMessage: "Description is required")]
    private string $description;
    #[Validation(required: true, errorMessage: "Transaction date is required")]
    private string $transaction_date;
}