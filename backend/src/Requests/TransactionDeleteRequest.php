<?php
declare(strict_types=1);

namespace App\Requests;

use App\Attributes\Validation;

class TransactionDeleteRequest
{
    #[Validation(required: true, errorMessage: "Id is required")]
    private int $id;
}