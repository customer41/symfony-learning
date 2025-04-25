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

    /**
     * @param StudentStats[] $studentStats
     */
    public function createBatch(array $studentStats): void
    {
        foreach ($studentStats as $statsSingleEntry) {
            $this->entityManager->persist($statsSingleEntry);
        }

        $this->flush();
    }

    public function findById(int $id): ?StudentStats
    {
        return $this->repositoryApi->find($id);
    }

    /**
     * @return StudentStats[]
     */
    public function findByIds(
        int|array|null $student,
        int|array|null $course = null,
        int|array|null $module = null,
        int|array|null $lesson = null,
        int|array|null $task = null,
        int|array|null $skill = null,
    ): array {
        $criteria = array_filter(get_defined_vars());

        return $this->repositoryApi->findBy($criteria);
    }
}
