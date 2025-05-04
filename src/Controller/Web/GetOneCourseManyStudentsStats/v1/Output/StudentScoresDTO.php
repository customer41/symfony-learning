<?php

namespace App\Controller\Web\GetOneCourseManyStudentsStats\v1\Output;

readonly class StudentScoresDTO
{
    public function __construct(
        public int $studentId,
        public string $studentName,
        public int $totalScore,
    ) {
    }
}
