<?php

namespace App\Controller\Web\GetOneCourseOneStudentStats\v1\Output;

use App\Controller\DTO\OutputDTOInterface;

readonly class OneCourseOneStudentDTO implements OutputDTOInterface
{
    /**
     * @param EducationItemOutputDTOInterface[] $educationItem
     */
    public function __construct(
        public int $studentId,
        public string $studentName,
        public int $courseId,
        public string $courseName,
        public int $totalScore,
        public array $educationItem,
    ) {
    }
}
