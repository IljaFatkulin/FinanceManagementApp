<?php
declare(strict_types=1);

namespace App\Exceptions;

class InvalidArgumentHttpException extends AbstractExceptionWithHttpStatus
{
    public function __construct(string $message = "Invalid argument", int $code = 0, ?Throwable $previous = null, int $httpStatus = 400)
    {
        parent::__construct($message, $code, $previous, $httpStatus);
    }
}