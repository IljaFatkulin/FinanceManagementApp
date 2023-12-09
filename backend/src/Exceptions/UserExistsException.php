<?php
declare(strict_types=1);

namespace App\Exceptions;

class UserExistsException extends AbstractExceptionWithHttpStatus
{
    public function __construct(string $message = "User already exists", int $code = 0, ?Throwable $previous = null, int $httpStatus = 409)
    {
        parent::__construct($message, $code, $previous, $httpStatus);
    }
}