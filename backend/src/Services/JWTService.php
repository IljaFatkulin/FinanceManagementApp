<?php
declare(strict_types=1);

namespace App\Services;

interface JWTService
{
    public function generate(string $email): string;

    // Method validate token and returns user email
    public function validate(string $token): string;
}