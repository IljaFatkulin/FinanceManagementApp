<?php
declare(strict_types=1);

namespace App\Core;

use Exception;

class DependencyInjectionContainer
{
    private static self $instance;

    private array $dependencies;

    private function __construct()
    {
        $this->dependencies = [];
    }

    public static function getContainer(): self
    {
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function addDependency(string $key, object $dependency): void
    {
        $this->dependencies[$key] = $dependency;
    }

    /**
     * @throws Exception
     */
    public function getDependency(string $key): object
    {
        if(!isset($this->dependencies[$key])) {
            throw new Exception("Dependency {$key} not found");
        }

        return $this->dependencies[$key];
    }
}