<?php

namespace App\Domain\Repository;

use App\Domain\DTO\StudentCourseDTO;
use App\Domain\DTO\StudentsCourseDTO;
use App\Domain\Entity\StudentStats;
use App\Domain\Model\OneCourseManyStudentsStatsModel;
use App\Domain\Model\OneCourseOneStudentStatsModel;

interface StudentStatsRepositoryInterface
{
    public function create(StudentStats $studentStats): int;

    /**
     * @param StudentStats[] $studentStats
     */
    public function createBatch(array $studentStats): void;

    public function findById(int $id): ?StudentStats;

    /**
     * @note: Each parameter is identifier(s) of related entities with stats
     *
     * @return StudentStats[]
     */
    public function findByIds(
        int|array|null $student,
        int|array|null $course = null,
        int|array|null $module = null,
        int|array|null $lesson = null,
        int|array|null $task = null,
        int|array|null $skill = null,
    ): array;

    public function getOneCourseManyStudentsAggregateStats(StudentsCourseDTO $studentsCourseDTO): OneCourseManyStudentsStatsModel;

    public function getOneCourseOneStudentAggregateStats(StudentCourseDTO $studentCourseDTO): OneCourseOneStudentStatsModel;
}
