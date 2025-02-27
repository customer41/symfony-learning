<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

class TaskRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Task::class);
    }

    public function create(Task $task): int
    {
        return $this->store($task);
    }

    public function findById(int $id): ?Task
    {
        return $this->repositoryApi->find($id);
    }
}
