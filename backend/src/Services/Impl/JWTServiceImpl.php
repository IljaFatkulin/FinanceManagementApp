<?php
declare(strict_types=1);

namespace App\Services\Impl;

use App\Exceptions\InvalidArgumentHttpException;
use App\Services\JWTService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTServiceImpl implements JWTService
{
    private string $secretKey = 'mySecr3t K3Y^^(*! :)';
    private string $serverName = 'http://localhost/untitled';
    private string $algorithm = 'HS512';

    public function generate(string $email): string
    {
        $issuedAt = new \DateTimeImmutable();
        $expire = $issuedAt->modify('+60 minutes')->getTimestamp();
        $username = $email;

        $payload = [
            'iss' => $this->serverName, // Issuer
            'iat' => $issuedAt->getTimestamp(), // Issued at
            'exp' => $expire,
            'email' => $username
        ];

        return JWT::encode(
            $payload,
            $this->secretKey,
            $this->algorithm
        );
    }

    public function validate(string $token): string
    {
        try {
            $payload = JWT::decode($token, new Key($this->secretKey, $this->algorithm));
        } catch (\Exception $e) {
            throw new InvalidArgumentHttpException("Invalid JWT token");
        }
        return $payload->email;
    }
}