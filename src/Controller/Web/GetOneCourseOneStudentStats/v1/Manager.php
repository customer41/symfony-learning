<?php

namespace App\Controller\Web\GetOneCourseOneStudentStats\v1;

use App\Controller\Web\GetOneCourseOneStudentStats\v1\Input\StudentCourseDTO;
use App\Controller\Web\GetOneCourseOneStudentStats\v1\Output\ModuleScoresDTO;
use App\Controller\Web\GetOneCourseOneStudentStats\v1\Output\OneCourseOneStudentDTO;
use App\Controller\Web\GetOneCourseOneStudentStats\v1\Output\TaskScoresDTO;
use App\Domain\DTO\StudentCourseDTO as InternalStudentCourseDTO;
use App\Domain\DTO\EducationItemInterface;
use App\Domain\DTO\ModuleScoresDTO as InternalModuleScoresDTO;
use App\Domain\Enum\EducationItem;
use App\Domain\Service\StudentStatsService;

class Manager
{
    public function __construct(private readonly StudentStatsService $studentStatsService) {}

    public function getOneCourseOneStudentStats(StudentCourseDTO $studentCourseDTO): OneCourseOneStudentDTO
    {
        $oneCourseOneStudentStatsModel = $this->studentStatsService->getOneCourseOneStudentStats(
            new InternalStudentCourseDTO(
                $studentCourseDTO->courseId,
                $studentCourseDTO->studentId,
                EducationItem::from($studentCourseDTO->aggregateBy),
            ),
        );

        return new OneCourseOneStudentDTO(
            $oneCourseOneStudentStatsModel->studentId,
            $oneCourseOneStudentStatsModel->studentName,
            $oneCourseOneStudentStatsModel->courseId,
            $oneCourseOneStudentStatsModel->courseName,
            $oneCourseOneStudentStatsModel->totalScore,
            array_map(
                static function(EducationItemInterface $educationItem) {
                    return $educationItem instanceof InternalModuleScoresDTO
                        ? new ModuleScoresDTO($educationItem->moduleId, $educationItem->moduleName, $educationItem->totalScore)
                        : new TaskScoresDTO(
                            $educationItem->taskId,
                            $educationItem->taskName,
                            $educationItem->lessonId,
                            $educationItem->lessonName,
                            $educationItem->totalScore,
                        );
                },
                $oneCourseOneStudentStatsModel->educationItems,
            )
        );
    }
}
