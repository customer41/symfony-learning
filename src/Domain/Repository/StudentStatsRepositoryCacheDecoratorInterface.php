<?php

namespace App\Domain\Repository;

use App\Domain\DTO\StudentCourseDTO;
use App\Domain\DTO\StudentsCourseDTO;
use App\Domain\Model\OneCourseManyStudentsStatsModel;
use App\Domain\Model\OneCourseOneStudentStatsModel;

interface StudentStatsRepositoryCacheDecoratorInterface
{
    public function getOneCourseManyStudentsAggregateStats(StudentsCourseDTO $studentsCourseDTO): OneCourseManyStudentsStatsModel;

    public function getOneCourseOneStudentAggregateStats(StudentCourseDTO $studentCourseDTO): OneCourseOneStudentStatsModel;
}
