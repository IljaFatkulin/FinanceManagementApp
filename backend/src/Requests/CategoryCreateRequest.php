<?php
declare(strict_types=1);

namespace App\Requests;

use App\Attributes\Validation;

class CategoryCreateRequest
{
    #[Validation(required: true, errorMessage: "Name is required")]
    private string $name;
}