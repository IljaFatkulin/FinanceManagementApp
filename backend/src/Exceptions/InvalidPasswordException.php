<?php
declare(strict_types=1);

namespace App\Exceptions;

class InvalidPasswordException extends AbstractExceptionWithHttpStatus
{
    public function __construct(string $message = "Invalid password", int $code = 0, ?Throwable $previous = null, int $httpStatus = 401)
    {
        parent::__construct($message, $code, $previous, $httpStatus);
    }
}