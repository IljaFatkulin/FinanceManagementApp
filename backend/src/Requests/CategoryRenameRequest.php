<?php
declare(strict_types=1);

namespace App\Requests;

use App\Attributes\Validation;

class CategoryRenameRequest
{
    #[Validation(required: true, errorMessage: "Id is required")]
    private int $id;
    #[Validation(required: true, errorMessage: "New name is required")]
    private string $new_name;
}