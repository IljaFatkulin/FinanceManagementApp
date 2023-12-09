<?php
declare(strict_types=1);

namespace App\Services;

use App\Exceptions\InvalidArgumentHttpException;
use App\Exceptions\UserExistsException;
use App\Exceptions\UserNotFound;
use App\Models\User;

interface UserService
{
    // Method should register user and return saved user, in case of errors an exception should be thrown
    /**
     * @throws UserExistsException
     */
    public function register(User $user): User;

    // Find methods returns user or if not found throw exception
    /**
     * @throws UserNotFound
     */
    public function findByEmail(string $email): User;
    /**
     * @throws UserNotFound
     */
    public function findById(int $id): User;

    /**
     * @throws UserNotFound
     */
    // Method return array ["user" => userData, "token" => JWT token]
    public function authorize(string $email, string $password): array;

    /**
     * @throws UserNotFound
     */
    public function changePassword(string $email, string $oldPassword, string $newPassword): void;

    // Function only sends verification code on user email
    /**
     * @throws UserNotFound
     * @throws \Exception
     */
    public function resetPassword(string $email): void;

    /**
     * @throws InvalidArgumentHttpException
     * @throws UserNotFound
     */
    public function resetPasswordConfirm(string $email, string $newPassword, string $verificationCode): void;

    // Method verify token and returns user or throw exception
    /**
     * @throws UserNotFound
     */
    public function verifyToken(string $token): User;
}