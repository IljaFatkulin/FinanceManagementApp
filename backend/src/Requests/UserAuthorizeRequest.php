<?php
declare(strict_types=1);

namespace App\Requests;

use App\Attributes\Validation;

class UserAuthorizeRequest
{
    #[Validation(required: true, errorMessage: "Email is required")]
    private string $email;
    #[Validation(required: true, errorMessage: "Password is required")]
    private string $password;
}