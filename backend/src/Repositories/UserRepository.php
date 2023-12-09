<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;

interface UserRepository
{
    public function create(User $user): User;

    // Find methods should return User or null if it is not found
    public function findByEmail(string $email): ?User;
    public function findById(int $id): ?User;

    public function update(User $user): User;
}