<?php
declare(strict_types=1);

namespace App\Core;

use App\Attributes\Route;
use ReflectionException;

class Router
{
    private array $routes;

    /**
     * @throws ReflectionException
     */
    public function registerAttributeRoutes(array $controllers): void {
        $container = DependencyInjectionContainer::getContainer();

        foreach ($controllers as $controllerClass) {
            $reflectionController = new \ReflectionClass($controllerClass);

            if($reflectionController->getConstructor() && $reflectionController->getConstructor()->getParameters()) {
                $parameters = $reflectionController->getConstructor()->getParameters();

                $arguments = [];
                foreach ($parameters as $parameter) {
                    $parameterName = $parameter->getName();
                    if(strlen($parameterName) > 0) {
                        if($container->getDependency($parameterName) === null) {
                            throw new \Exception('Dependency {$parameterName} not found');
                        }

                        $arguments[] = $container->getDependency($parameterName);
                    }
                }
                $controller = $reflectionController->newInstanceArgs($arguments);
            } else {
                $controller = $reflectionController->newInstance();
            }

            foreach ($reflectionController->getMethods() as $method) {
                $attributes = $method->getAttributes(Route::class);

                foreach ($attributes as $attribute) {
                    $route = $attribute->newInstance();

                    $this->register($route->method, $route->path, [$controller, $method->getName()]);
                }
            }
        }
    }

    private function register(string $requestMethod, string $route, array $action): void
    {
        $this->routes[$requestMethod][ROUTE . $route] = $action;
    }

    public function get(string $route, array $action): void
    {
        $this->register('GET', $route, $action);
    }

    public function post(string $route, array $action): void
    {
        $this->register('POST', $route, $action);
    }

    public function resolve(string $requestUri, string $requestMethod)
    {
        $route = explode('?', $requestUri)[0];
        $action = $this->routes[$requestMethod][$route] ?? null;

        if(is_array($action)) {
            [$class, $method] = $action;
            if(is_object($class)) {
                if(method_exists($class, $method)) {
                    return call_user_func_array([$class, $method], []);
                }
            } else if(class_exists($class)) {
                $class = new $class;
                if(method_exists($class, $method)) {
                    return call_user_func_array([$class, $method], []);
                }
            }
        }

        return null;
    }
}