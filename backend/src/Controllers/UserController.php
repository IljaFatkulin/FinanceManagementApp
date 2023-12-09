<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Route;
use App\Core\Controller;
use App\Core\Http\HttpRequest;
use App\Exceptions\InvalidArgumentHttpException;
use App\Exceptions\UserExistsException;
use App\Exceptions\UserNotFound;
use App\Models\User;
use App\Requests\UserAuthorizeByTokenRequest;
use App\Requests\UserAuthorizeRequest;
use App\Requests\UserChangePasswordRequest;
use App\Requests\UserRegisterRequest;
use App\Requests\UserResetPasswordConfirmRequest;
use App\Requests\UserResetPasswordRequest;
use App\Services\UserService;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    /**
     * @throws UserNotFound
     * @throws \Exception
     */
    #[Route('/user')]
    public function findUserByEmail(): void
    {
        $email = HttpRequest::getParam("email");

        $user = $this->userService->findByEmail($email);
        $this->response(["user" => $user]);
    }

    /**
     * @throws UserExistsException
     */
    #[Route('/users', 'POST')]
    public function createUser(): void
    {
        $requestBody = HttpRequest::getBody();

        // Validation
        if(!$this->validate(UserRegisterRequest::class, $requestBody)) return;

        $user = new User(null, $requestBody['email'], $requestBody['password'], $requestBody['firstname'], $requestBody['lastname']);

        $user = $this->userService->register($user);
        $this->response(["user" => $user], 201);
    }

    /**
     * @throws UserNotFound
     */
    #[Route('/authorize', 'POST')]
    public function authorizeUser(): void
    {
        $requestBody = HttpRequest::getBody();

        // Validation
        if(!$this->validate(UserAuthorizeRequest::class, $requestBody)) return;

        $userData = $this->userService->authorize($requestBody['email'], $requestBody['password']);
        $this->response($userData);
    }

    /**
     * @throws UserNotFound
     */
    #[Route('/password/change', 'POST')]
    public function changePassword(): void
    {
        $requestBody = HttpRequest::getBody();

        if(!$this->validate(UserChangePasswordRequest::class, $requestBody)) return;

        $this->userService->changePassword($requestBody['email'], $requestBody['old_password'], $requestBody['new_password']);
    }

    // Endpoint sends verification code on user email to reset password
    /**
     * @throws UserNotFound
     */
    #[Route('/password/reset', 'POST')]
    public function resetPassword(): void
    {
        $requestBody = HttpRequest::getBody();

        if(!$this->validate(UserResetPasswordRequest::class, $requestBody)) return;

        $this->userService->resetPassword($requestBody['email']);
    }

    /**
     * @throws UserNotFound
     * @throws InvalidArgumentHttpException
     */
    #[Route('/password/reset/confirm', 'POST')]
    public function resetPasswordConfirm(): void
    {
        $requestBody = HttpRequest::getBody();

        if(!$this->validate(UserResetPasswordConfirmRequest::class, $requestBody)) return;

        $this->userService->resetPasswordConfirm($requestBody['email'], $requestBody['new_password'], $requestBody['verification_code']);
    }

    /**
     * @throws UserNotFound
     */
    #[Route('/authorize/token', 'POST')]
    public function authorizeByToken(): void
    {
        $requestBody = HttpRequest::getBody();

        if(!$this->validate(UserAuthorizeByTokenRequest::class, $requestBody)) return;

        $user = $this->userService->verifyToken($requestBody['token']);
        $this->response(["user" => $user]);
    }
}