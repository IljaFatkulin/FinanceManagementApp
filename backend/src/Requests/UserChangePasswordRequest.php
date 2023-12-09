<?php
declare(strict_types=1);

namespace App\Requests;

use App\Attributes\Validation;

class UserChangePasswordRequest
{
    #[Validation(required: true, errorMessage: "Email is required")]
    private string $email;
    #[Validation(required: true, errorMessage: "Old password is required")]
    private string $old_password;
    #[Validation(required: true, errorMessage: "New password is required")]
    private string $new_password;
}