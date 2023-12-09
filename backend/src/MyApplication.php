<?php
declare(strict_types=1);

namespace App;

use App\Core\Application;
use App\Core\DependencyInjectionContainer;
use App\Repositories\Impl\CategoryRepositoryImpl;
use App\Repositories\Impl\RoleRepositoryImpl;
use App\Repositories\Impl\TransactionRepositoryImpl;
use App\Repositories\Impl\UserRepositoryImpl;
use App\Security\SecurityConfig;
use App\Services\Impl\CategoryServiceImpl;
use App\Services\Impl\EmailServiceImpl;
use App\Services\Impl\JWTServiceImpl;
use App\Services\Impl\TransactionServiceImpl;
use App\Services\Impl\UserServiceImpl;
use Firebase\JWT\JWT;

class MyApplication extends Application
{
    /**
     * @throws \Exception
     */
    public function run(): void
    {
        $container = DependencyInjectionContainer::getContainer();

        $container->addDependency('roleRepository', new RoleRepositoryImpl());
        $container->addDependency('userRepository', new UserRepositoryImpl());
        $container->addDependency('emailService', new EmailServiceImpl());
        $container->addDependency('jwtService', new JWTServiceImpl());
        $container->addDependency('userService',
            new UserServiceImpl(
                $container->getDependency('userRepository'),
                $container->getDependency('roleRepository'),
                $container->getDependency('emailService'),
                $container->getDependency('jwtService')
            )
        );

        $container->addDependency('categoryRepository', new CategoryRepositoryImpl());
        $container->addDependency('categoryService',
            new CategoryServiceImpl(
                $container->getDependency('categoryRepository')
            )
        );

        $container->addDependency('transactionRepository', new TransactionRepositoryImpl());
        $container->addDependency('transactionService',
            new TransactionServiceImpl(
                $container->getDependency('transactionRepository'),
                $container->getDependency('userService'),
                $container->getDependency('categoryService')
            )
        );

        $this->start(new SecurityConfig());
    }
}