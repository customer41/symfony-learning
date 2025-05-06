<?php

namespace App\Infrastructure\Repository;

use App\Domain\DTO\StudentCourseDTO;
use App\Domain\DTO\ModuleScoresDTO;
use App\Domain\DTO\StudentCourseScoresDTO;
use App\Domain\DTO\StudentScoresDTO;
use App\Domain\DTO\StudentsCourseDTO;
use App\Domain\DTO\TaskScoresDTO;
use App\Domain\Entity\Course;
use App\Domain\Entity\StudentStats;
use App\Domain\Enum\EducationItem;
use App\Domain\Model\OneCourseManyStudentsStatsModel;
use App\Domain\Model\OneCourseOneStudentStatsModel;
use App\Domain\Repository\StudentStatsRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class StudentStatsRepository extends AbstractRepository implements StudentStatsRepositoryInterface
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

    public function getOneCourseManyStudentsAggregateStats(StudentsCourseDTO $studentsCourseDTO): OneCourseManyStudentsStatsModel
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $studentScores = $queryBuilder
            ->select(
                sprintf(
                    'NEW %s(student.id, CONCAT(user.firstName, \' \', user.lastName), SUM(stats.score))',
                    StudentScoresDTO::class,
                )
            )
            ->from(StudentStats::class, 'stats')
            ->join('stats.course', 'course')
            ->join('stats.student', 'student')
            ->join('student.user', 'user')
            ->where('stats.course = :courseId')
            ->andWhere($queryBuilder->expr()->between('stats.createdAt', ':startDate', ':endDate'))
            ->groupBy('student.id, user.id')
            ->setParameter('courseId', $studentsCourseDTO->courseId)
            ->setParameter('startDate', $studentsCourseDTO->period->getStartDate()->format('Y-m-d'))
            ->setParameter('endDate', $studentsCourseDTO->period->getEndDate()->modify('+1 day')->format('Y-m-d'))
            ->getQuery()
            ->getResult();

        $queryBuilder->resetDQLParts();
        $queryBuilder->getParameters()->clear();

        $courseName = $queryBuilder
            ->select('course.title')
            ->from(Course::class, 'course')
            ->where('course.id = :courseId')
            ->setParameter('courseId', $studentsCourseDTO->courseId)
            ->getQuery()
            ->getSingleScalarResult();

        return new OneCourseManyStudentsStatsModel(
            $studentsCourseDTO->courseId,
            $courseName,
            $studentsCourseDTO->period,
            $studentScores
        );
    }

    public function getOneCourseOneStudentAggregateStats(StudentCourseDTO $studentCourseDTO): OneCourseOneStudentStatsModel
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from(StudentStats::class, 'stats');

        switch ($studentCourseDTO->aggregateBy) {
            case EducationItem::Module:
                $queryBuilder
                    ->select(sprintf('NEW %s(module.id, module.title, SUM(stats.score))', ModuleScoresDTO::class))
                    ->join('stats.module', 'module')
                    ->groupBy('module.id');
                break;
            case EducationItem::Task:
                $queryBuilder
                    ->select(
                        sprintf(
                            'NEW %s(task.id, task.title, lesson.id, lesson.title, SUM(stats.score))',
                            TaskScoresDTO::class
                        )
                    )
                    ->join('stats.task', 'task')
                    ->join('task.lesson', 'lesson')
                    ->groupBy('task.id, lesson.id');
        }

        $educationItems = $queryBuilder
            ->where('stats.course = :courseId')
            ->andWhere('stats.student = :studentId')
            ->setParameter('courseId', $studentCourseDTO->courseId)
            ->setParameter('studentId', $studentCourseDTO->studentId)
            ->getQuery()
            ->getResult();

        $queryBuilder->resetDQLParts();

        /** @var StudentCourseScoresDTO $studentCourseScore */
        $studentCourseScore = $queryBuilder
            ->select(
                sprintf(
                    'NEW %s(student.id, CONCAT(user.firstName, \' \', user.lastName), course.id, course.title, SUM(stats.score))',
                    StudentCourseScoresDTO::class,
                )
            )
            ->from(StudentStats::class, 'stats')
            ->join('stats.course', 'course')
            ->join('stats.student', 'student')
            ->join('student.user', 'user')
            ->where('stats.course = :courseId')
            ->andWhere('stats.student = :studentId')
            ->groupBy('student.id, user.id, course.id')
            ->setParameter('courseId', $studentCourseDTO->courseId)
            ->setParameter('studentId', $studentCourseDTO->studentId)
            ->getQuery()
            ->getOneOrNullResult();

        return new OneCourseOneStudentStatsModel(
            $studentCourseScore->studentId,
            $studentCourseScore->studentName,
            $studentCourseScore->courseId,
            $studentCourseScore->courseName,
            $studentCourseScore->totalScore,
            $educationItems,
        );
    }
}
