<?php
declare(strict_types=1);

namespace App\Core\Security;

class Route
{
    private string $method;
    private string $route;
    private bool $isPublic;
    private array $roles;

    private function __construct(string $method, string $route) {
        $this->method = $method;
        $this->route = $route;
        $this->isPublic = false;
        $this->roles = [];
    }

    public static function create(string $method, string $route): self
    {
        return new self($method, ROUTE . $route);
    }

    public function permitAll(): self
    {
        $this->isPublic = true;
        return $this;
    }

    public function authenticated(): self
    {
        $this->isPublic = false;
        return $this;
    }

    public function hasAnyRole(string ...$roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }


}