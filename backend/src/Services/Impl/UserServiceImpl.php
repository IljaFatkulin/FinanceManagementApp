<?php
declare(strict_types=1);

namespace App\Services\Impl;

use App\Exceptions\InvalidArgumentHttpException;
use App\Exceptions\InvalidPasswordException;
use App\Exceptions\UserExistsException;
use App\Exceptions\UserNotFound;
use App\Models\User;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\Services\JWTService;
use \App\Services\UserService;

class UserServiceImpl implements UserService
{
    private UserRepository $userRepository;
    private RoleRepository $roleRepository;
    private EmailService $emailService;
    private JWTService $jwtService;

    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository, EmailService $emailService, JWTService $jwtService)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->emailService = $emailService;
        $this->jwtService = $jwtService;
    }

    /**
     * @throws UserExistsException
     */
    public function register(User $user): User
    {
        if($this->userRepository->findByEmail($user->getEmail()) !== null) {
            throw new UserExistsException();
        }

        $role = $this->roleRepository->findByName('user');

        $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));
        $user->setRole($role);
        $user->setCreatedAt(new \DateTime());

        return $this->userRepository->create($user);
    }

    /**
     * @throws UserNotFound
     */
    public function findByEmail(string $email): User
    {
        $user = $this->userRepository->findByEmail($email);

        if($user === null) throw new UserNotFound();

        return $user;
    }

    /**
     * @throws UserNotFound
     */
    public function findById(int $id): User
    {
        $user = $this->userRepository->findById($id);

        if($user === null) throw new UserNotFound();

        return $user;
    }

    /**
     * @throws InvalidPasswordException
     * @throws UserNotFound
     */
    public function authorize(string $email, string $password): array
    {
        $user = $this->findByEmail($email);

        if(!password_verify($password, $user->getPassword())) {
            throw new InvalidPasswordException();
        }

        return [
            "user" => $user,
            "token" => $this->jwtService->generate($email)
        ];
    }

    /**
     * @throws UserNotFound
     * @throws InvalidPasswordException
     */
    public function changePassword(string $email, string $oldPassword, string $newPassword): void
    {
        $user = $this->findByEmail($email);

        if(!password_verify($oldPassword, $user->getPassword())) {
            throw new InvalidPasswordException();
        }

        $user->setPassword(password_hash($newPassword, PASSWORD_DEFAULT));
        $this->userRepository->update($user);
    }

    /**
     * @throws UserNotFound
     * @throws \Exception
     */
    public function resetPassword(string $email): void
    {
        $user = $this->findByEmail($email);

        $code = random_int(100000, 999999);
        $user->setVerificationCode(strval($code));
        $this->userRepository->update($user);

        $this->emailService->sendEmail($email, "Reset password", "Code: " . $code);
    }

    /**
     * @throws InvalidArgumentHttpException
     * @throws UserNotFound
     */
    public function resetPasswordConfirm(string $email, string $newPassword, string $verificationCode): void
    {
        $user = $this->findByEmail($email);

        if($user->getVerificationCode() !== $verificationCode) {
            throw new InvalidArgumentHttpException("Invalid verification code");
        }

        $user->setVerificationCode(null);
        $user->setPassword(password_hash($newPassword, PASSWORD_DEFAULT));
        $this->userRepository->update($user);
    }


    /**
     * @throws UserNotFound
     */
    public function verifyToken(string $token): User
    {
        $email = $this->jwtService->validate($token);

        return $this->findByEmail($email);

    }
}