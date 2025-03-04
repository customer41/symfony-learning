<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Course;
use App\Domain\Enum\CourseStatus;
use Doctrine\ORM\EntityManagerInterface;

class CourseRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Course::class);
    }

    public function create(Course $course): int
    {
        return $this->store($course);
    }

    public function findById(int $id): ?Course
    {
        return $this->repositoryApi->find($id);
    }

    /**
     * @return Course[]
     */
    public function findAllPlannedWithinSomeTime(\DateTime $date): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        return $queryBuilder
            ->select('c')
            ->from(Course::class, 'c')
            ->where(sprintf("c.status = '%s'", CourseStatus::Planned->value))
            ->andWhere('c.startDate is not null')
            ->andWhere('c.startDate <= :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->getQuery()
            ->getResult();
    }
}
