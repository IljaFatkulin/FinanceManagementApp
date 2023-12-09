<?php
declare(strict_types=1);

namespace App\Exceptions;

abstract class AbstractExceptionWithHttpStatus extends \Exception
{
    private int $httpStatus;

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, int $httpStatus = 400)
    {
        parent::__construct($message, $code, $previous);
        $this->httpStatus = $httpStatus;
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }

    /**
     * @param int $httpStatus
     */
    public function setHttpStatus(int $httpStatus): void
    {
        $this->httpStatus = $httpStatus;
    }


}