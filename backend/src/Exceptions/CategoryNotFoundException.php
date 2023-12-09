<?php
declare(strict_types=1);

namespace App\Exceptions;

class CategoryNotFoundException extends NotFoundException
{
    public function __construct(string $message = "Category not found", int $code = 0, ?Throwable $previous = null, int $httpStatus = 404)
    {
        parent::__construct($message, $code, $previous, $httpStatus);
    }
}