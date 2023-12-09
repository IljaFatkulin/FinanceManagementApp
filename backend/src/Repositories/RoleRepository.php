<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Role;

interface RoleRepository
{
    // Find methods should return User or null if it is not found
    public function findById(int $id): ?Role;
    public function findByName(string $name): ?Role;
}