<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, User::class);
    }

    public function create(User $user): int
    {
        return $this->store($user);
    }

    public function findById(int $id): ?User
    {
        return $this->repositoryApi->find($id);
    }
}
