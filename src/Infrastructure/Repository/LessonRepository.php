<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Lesson;
use App\Domain\Enum\CourseStatus;
use Doctrine\ORM\EntityManagerInterface;

class LessonRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Lesson::class);
    }

    public function create(Lesson $lesson): int
    {
        return $this->store($lesson);
    }

    public function findById(int $id): ?Lesson
    {
        return $this->repositoryApi->find($id);
    }

    /**
     * @return Lesson[]
     */
    public function findAllWithTasksByCourseName(string $courseName): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        return $queryBuilder
            ->select('l', 't')
            ->from(Lesson::class, 'l')
            ->join('l.tasks', 't')
            ->leftJoin('l.module', 'm')
            ->leftJoin('m.course', 'c')
            ->where(sprintf("c.status = '%s'", CourseStatus::InProgress->value))
            ->andWhere("lower(c.title) like :title")
            ->setParameter('title', strtolower($courseName) . '%')
            ->getQuery()
            ->getResult();
    }
}
