<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\StudentTask;
use Doctrine\ORM\EntityManagerInterface;

class StudentTaskRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, StudentTask::class);
    }

    public function create(StudentTask $studentTask): int
    {
        return $this->store($studentTask);
    }

    public function findById(int $id): ?StudentTask
    {
        return $this->repositoryApi->find($id);
    }
}
