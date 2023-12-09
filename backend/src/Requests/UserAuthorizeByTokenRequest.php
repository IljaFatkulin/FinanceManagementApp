<?php
declare(strict_types=1);

namespace App\Requests;

use App\Attributes\Validation;

class UserAuthorizeByTokenRequest
{
    #[Validation(required: true, errorMessage: "Token is required")]
    private string $token;
}