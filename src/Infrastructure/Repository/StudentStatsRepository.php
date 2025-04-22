<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\StudentStats;
use Doctrine\ORM\EntityManagerInterface;

class StudentStatsRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, StudentStats::class);
    }

    public function create(StudentStats $studentStats): int
    {
        return $this->store($studentStats);
    }

    public function findById(int $id): ?StudentStats
    {
        return $this->repositoryApi->find($id);
    }
}
