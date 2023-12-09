<?php
declare(strict_types=1);

namespace App\Security;

use App\Core\Security\Route;
use App\Core\Security\Security;
use App\Core\Security\SecurityMethod;

class SecurityConfig extends Security
{

    protected function configureRoutes(): array
    {
        $this->setSecurityMethod(SecurityMethod::JWT);
//        $this->setSecurityMethod(SecurityMethod::BASIC);

        return [
            Route::create("POST", "/users")->permitAll(),
            Route::create("POST", "/authorize")->permitAll(),
            Route::create("POST", "/password/reset")->permitAll(),
            Route::create("POST", "/password/reset/confirm")->permitAll(),
            Route::create("POST", "/authorize/token")->permitAll(),
            $this->anyRoute()->authenticated()
        ];
    }
}