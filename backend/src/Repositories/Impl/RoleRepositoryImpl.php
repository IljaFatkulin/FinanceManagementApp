<?php
declare(strict_types=1);

namespace App\Repositories\Impl;

use App\Core\Repository;
use App\Models\Role;
use App\Repositories\RoleRepository;

class RoleRepositoryImpl extends Repository implements RoleRepository
{

    public function findById(int $id): ?Role
    {
        $stmt = $this->connection->prepare("SELECT * FROM role WHERE id = ?");
        $stmt->bind_param("i", $id);

        return $this->find($stmt);
    }

    public function findByName(string $name): ?Role
    {
        $stmt = $this->connection->prepare("SELECT * FROM role WHERE name = ?");
        $stmt->bind_param("s", $name);

        return $this->find($stmt);
    }

    private function find($stmt): ?Role
    {
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if($result === null) return null;

        return new Role(intval($result['id']), $result['name']);
    }
}