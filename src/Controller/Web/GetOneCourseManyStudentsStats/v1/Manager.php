<?php

namespace App\Controller\Web\GetOneCourseManyStudentsStats\v1;

use App\Controller\Web\GetOneCourseManyStudentsStats\v1\Input\StudentsCourseDTO;
use App\Controller\Web\GetOneCourseManyStudentsStats\v1\Output\OneCourseManyStudentsDTO;
use App\Controller\Web\GetOneCourseManyStudentsStats\v1\Output\StudentScoresDTO;
use App\Domain\DTO\StudentScoresDTO as InternalStudentScoresDTO;
use App\Domain\DTO\StudentsCourseDTO as InternalStudentsCourseDTO;
use App\Domain\Service\StudentStatsService;

class Manager
{
    public function __construct(private readonly StudentStatsService $studentStatsService) {}

    public function getOneCourseManyStudentsStats(StudentsCourseDTO $studentsCourseDTO, int $courseId): OneCourseManyStudentsDTO
    {
        $oneCourseManyStudentsStats = $this->studentStatsService->getOneCourseManyStudentsStats(
            new InternalStudentsCourseDTO(
                $courseId,
                new \DatePeriod(
                    $studentsCourseDTO->startDate,
                    \DateInterval::createFromDateString('1 day'),
                    $studentsCourseDTO->endDate,
                ),
                $studentsCourseDTO->sortOrder,
            ),
        );

        return new OneCourseManyStudentsDTO(
            $oneCourseManyStudentsStats->courseId,
            $oneCourseManyStudentsStats->courseName,
            $oneCourseManyStudentsStats->avgScore,
            $oneCourseManyStudentsStats->maxScore,
            "{$oneCourseManyStudentsStats->period->getStartDate()->format('d.m.Y')}"
                . " - {$oneCourseManyStudentsStats->period->getEndDate()->format('d.m.Y')}",
            array_map(
                static fn(InternalStudentScoresDTO $studentScoresDTO) => new StudentScoresDTO(
                    $studentScoresDTO->studentId,
                    $studentScoresDTO->studentName,
                    $studentScoresDTO->totalScore,
                ),
                $oneCourseManyStudentsStats->studentScores,
            ),
        );
    }
}
