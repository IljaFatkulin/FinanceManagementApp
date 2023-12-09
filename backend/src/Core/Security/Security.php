<?php
declare(strict_types=1);

namespace App\Core\Security;

use App\Core\DependencyInjectionContainer;
use App\Exceptions\AbstractExceptionWithHttpStatus;
use App\Exceptions\ForbiddenException;
use App\Exceptions\InvalidArgumentHttpException;
use App\Exceptions\UserNotFound;
use App\Models\User;
use App\Services\JWTService;
use App\Services\UserService;

abstract class Security
{
    private UserService $userService;
    private JWTService $jwtService;

    private array $routes;
    private ?Route $anyRoute;
    private static ?string $username = null;

    private SecurityMethod $securityMethod = SecurityMethod::BASIC;

    public function exceptionHandler(\Throwable $e): void
    {
        if($e instanceof AbstractExceptionWithHttpStatus) {
            echo json_encode($e->getMessage());
            http_response_code($e->getHttpStatus());
            die();
        } else {
            echo $e->getMessage();
        }
    }

    public function __construct()
    {
        if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            die();
        }

        set_exception_handler(array($this, 'exceptionHandler'));

        $this->userService = DependencyInjectionContainer::getContainer()->getDependency('userService');
        $this->jwtService = DependencyInjectionContainer::getContainer()->getDependency('jwtService');
        $this->anyRoute = null;
        $this->start();
    }

    public function setSecurityMethod(SecurityMethod $method): void
    {
        $this->securityMethod = $method;
    }

    /**
     * @throws ForbiddenException
     * @throws UserNotFound
     */
    public function start():void
    {
        $this->setRoutes($this->configureRoutes());
        $this->handleRoute();
    }

    abstract protected function configureRoutes(): array;

    protected  function anyRoute(): Route
    {
        $this->anyRoute = Route::create('*', '*')->permitAll();
        return $this->anyRoute;
    }

    /**
     * @throws UserNotFound
     * @throws ForbiddenException
     */
    private function handleRoute(): void
    {
        $route = $this->findRoute($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'])['path']);

        if($route !== null) {
            $this->checkAccess($route);
        } else if($this->anyRoute !== null) {
            $this->checkAccess($this->anyRoute);
        }
    }

    /**
     * @throws UserNotFound
     * @throws ForbiddenException
     */
    private function checkAccess(Route $route): void
    {
        if(! $route->isPublic()) {
            $username = match ($this->securityMethod) {
                SecurityMethod::JWT => $this->jwtAuth(),
                default => $this->basicAuth(),
            };

            self::$username = $username;

            $roles = $route->getRoles();
            if(count($roles) > 0) {
                $user = $this->userService->findByEmail($username);
                if(! in_array($user->getRole(), $roles)) {
                    throw new ForbiddenException();
                }
            }
        }
    }

    /**
     * @throws ForbiddenException
     */
    private function jwtAuth(): string
    {
        if(!isset($_SERVER['HTTP_AUTHORIZATION'])) {
//            throw new InvalidArgumentHttpException("Invalid JWT token");
            throw new ForbiddenException();
        }

        if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
//            throw new InvalidArgumentHttpException("Invalid JWT token");
            throw new ForbiddenException();
        }

        $jwt = $matches[1];
        try {
            $username = $this->jwtService->validate($jwt);
            $this->userService->verifyToken($jwt);
        } catch (\Exception) {
            throw new ForbiddenException();
        }
        return $username;
    }

    /**
     * @throws ForbiddenException
     * @throws UserNotFound
     */
    private function basicAuth(): string
    {
        $username = $_SERVER['PHP_AUTH_USER'] ?? null;
        $password = $_SERVER['PHP_AUTH_PW'] ?? null;

        if ($username === null || $password === null) {
            throw new ForbiddenException();
        }

        if(! $this->userService->authorize($username, $password)) {
            throw new ForbiddenException();
        }

        return $username;
    }

    private function findRoute(string $method, string $route): ?Route
    {
        foreach ($this->routes as $routeObj) {
            if($routeObj->getMethod() === $method && $routeObj->getRoute() === $route) {
                return $routeObj;
            }
        }

        return null;
    }

    /**
     * @param array $routes
     */
    private function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }

    /**
     * @return string|null
     */
    public static function getUsername(): ?string
    {
        return self::$username;
    }

}