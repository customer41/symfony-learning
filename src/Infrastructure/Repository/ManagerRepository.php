<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Manager;
use Doctrine\ORM\EntityManagerInterface;

class ManagerRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Manager::class);
    }

    public function create(Manager $manager): int
    {
        return $this->store($manager);
    }

    public function findById(int $id): ?Manager
    {
        return $this->repositoryApi->find($id);
    }
}
