<?php

namespace App\Domain\Model;

use App\Domain\DTO\EducationItemInterface;

readonly class OneCourseOneStudentStatsModel
{
    /**
     * @param EducationItemInterface[] $educationItems
     */
    public function __construct(
        public int $studentId,
        public string $studentName,
        public int $courseId,
        public string $courseName,
        public int $totalScore,
        public array $educationItems,
    ) {
    }
}
