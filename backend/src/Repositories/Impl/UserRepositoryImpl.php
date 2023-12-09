<?php
declare(strict_types=1);

namespace App\Repositories\Impl;

use App\Core\DependencyInjectionContainer;
use App\Core\Repository;
use App\Models\User;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use DateTime;

class UserRepositoryImpl extends Repository implements UserRepository
{
    private RoleRepository $roleRepository;

    public function __construct()
    {
        parent::__construct();
        $this->roleRepository = DependencyInjectionContainer::getContainer()->getDependency('roleRepository');
    }

    public function create(User $user): User
    {
        $email = $user->getEmail();
        $password = $user->getPassword();
        $firstname = $user->getFirstname();
        $lastname = $user->getLastname();
        $createdAt = $user->getCreatedAt()->format("Y-m-d H:i:s");
        $roleId = $user->getRole()->getId();

        $stmt = $this->connection->prepare("INSERT INTO user(email, password, firstname, lastname, created_at, role_id) VALUES(?,?,?,?,?,?)");
        $stmt->bind_param("sssssi", $email, $password, $firstname, $lastname, $createdAt, $roleId);
        $stmt->execute();

        $user->setId($this->connection->insert_id);

        return $user;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->connection->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);

        return $this->find($stmt);
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->connection->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->bind_param("i", $id);

        return $this->find($stmt);
    }

    private function find($stmt): ?User
    {
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if($result === null) return null;

        $role = $this->roleRepository->findById(intval($result['role_id']));

        return new User(intval($result['id']), $result['email'], $result['password'], $result['firstname'], $result['lastname'], new DateTime($result['created_at']), $role, $result['verification_code']);
    }

    public function update(User $user): User
    {
        $email = $user->getEmail();
        $password = $user->getPassword();
        $firstname = $user->getFirstname();
        $lastname = $user->getLastname();
        $roleId = $user->getRole()->getId();
        $verificationCode = $user->getVerificationCode();

        $stmt = $this->connection->prepare("UPDATE user SET password = ?, firstname = ?, lastname = ?, role_id = ?, verification_code = ? WHERE email = ?");
        $stmt->bind_param("sssiss", $password, $firstname, $lastname, $roleId, $verificationCode, $email);
        $stmt->execute();

        $user->setId($this->connection->insert_id);
        return $user;
    }
}