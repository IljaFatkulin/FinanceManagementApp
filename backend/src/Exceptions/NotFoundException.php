<?php
declare(strict_types=1);

namespace App\Exceptions;

class NotFoundException extends AbstractExceptionWithHttpStatus
{
    public function __construct(string $message = "Not found", int $code = 0, ?Throwable $previous = null, int $httpStatus = 404)
    {
        parent::__construct($message, $code, $previous, $httpStatus);
    }
}