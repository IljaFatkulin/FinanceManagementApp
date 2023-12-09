<?php
declare(strict_types=1);

namespace App\Exceptions;

class DuplicateKeyException extends AbstractExceptionWithHttpStatus
{
    public function __construct(string $message = "Duplicate key", int $code = 0, ?Throwable $previous = null, int $httpStatus = 409)
    {
        parent::__construct($message, $code, $previous, $httpStatus);
    }
}