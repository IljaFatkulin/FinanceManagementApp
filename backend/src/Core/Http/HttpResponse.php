<?php
declare(strict_types=1);

namespace App\Core\Http;

class HttpResponse
{
    private $body;
    private $status;

    public function __construct($body, $status = 200)
    {
        $this->body = $body;
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }

    /**
     * @return int|mixed
     */
    public function getStatus(): mixed
    {
        return $this->status;
    }

    /**
     * @param int|mixed $status
     */
    public function setStatus(mixed $status): void
    {
        $this->status = $status;
    }


}