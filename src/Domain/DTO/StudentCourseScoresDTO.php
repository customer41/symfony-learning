<?php

namespace App\Domain\DTO;

readonly class StudentCourseScoresDTO
{
    public function __construct(
        public int $studentId,
        public string $studentName,
        public int $courseId,
        public string $courseName,
        public int $totalScore,
    ) {
    }
}
