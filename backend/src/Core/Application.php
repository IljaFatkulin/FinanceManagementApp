<?php
declare(strict_types=1);

namespace App\Core;
use App\Core\Security\Security;
use App\Exceptions\AbstractExceptionWithHttpStatus;
use App\Exceptions\ForbiddenException;
use App\Exceptions\UserNotFound;
use App\Security\SecurityConfig;

abstract class Application
{
    abstract function run(): void;

    public function start(Security $security): void
    {
        $request_uri = $_SERVER['REQUEST_URI'];
        $request_method = $_SERVER['REQUEST_METHOD'];

        $router = new Router();

        $this->registerControllers($router);

        $router->resolve($request_uri, $request_method);
    }

    private function registerControllers(Router $router): void
    {
        $controllersDir = '../src/Controllers';

        foreach (new \DirectoryIterator($controllersDir) as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $classFullName = 'App\\Controllers\\' . substr($file->getFilename(), 0, -4);

                $router->registerAttributeRoutes([$classFullName]);
            }
        }
    }
}