<?php
declare(strict_types=1);

namespace App\Core\Http;

class HttpRequest
{
    public static function getBody(): ?array
    {
        $request_body = file_get_contents('php://input');
        return json_decode($request_body, true);
    }

    public static function getAllParams(): array
    {
        return $_GET;
    }

    public static function getParam(string $name)
    {
        if(!isset($_GET[$name])) {
            throw new \Exception('Parameter not set');
        }

        return $_GET[$name];
    }
}