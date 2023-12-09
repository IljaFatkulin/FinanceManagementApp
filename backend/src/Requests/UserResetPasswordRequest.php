<?php
declare(strict_types=1);

namespace App\Requests;

use App\Attributes\Validation;

class UserResetPasswordRequest
{
    #[Validation(required: true, errorMessage: "Email is required")]
    private string $email;
}