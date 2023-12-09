<?php
declare(strict_types=1);

namespace App\Requests;

use App\Attributes\Validation;

class UserRegisterRequest
{
    #[Validation(required: true, errorMessage: "Email is required")]
    private ?string $email;
    #[Validation(required: true, errorMessage: "Password is required")]
    private ?string $password;
    #[Validation(required: true, errorMessage: "Firstname is required")]
    private ?string $firstname;
    #[Validation(required: true, errorMessage: "Lastname is required")]
    private ?string $lastname;
}