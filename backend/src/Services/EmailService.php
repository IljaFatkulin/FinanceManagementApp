<?php
declare(strict_types=1);

namespace App\Services;

interface EmailService
{
    // $messageBody can be with html tags
    public function sendEmail(string $receiverEmail, string $subject, string $messageBody): void;
}