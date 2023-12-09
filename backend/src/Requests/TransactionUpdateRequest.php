<?php
declare(strict_types=1);

namespace App\Requests;

use App\Attributes\Validation;

class TransactionUpdateRequest
{
    #[Validation(required: true, errorMessage: "Id is required")]
    private int $id;
    #[Validation(required: true, errorMessage: "Type is required")]
    private string $type;
    #[Validation(required: true, errorMessage: "Amount is required")]
    private float $amount;
    #[Validation(required: true, errorMessage: "Description is required")]
    private string $description;
    #[Validation(required: true, errorMessage: "Transaction t")]
    private string $transaction_date;
}