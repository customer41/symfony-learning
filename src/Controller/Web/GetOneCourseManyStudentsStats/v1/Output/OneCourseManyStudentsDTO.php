<?php

namespace App\Controller\Web\GetOneCourseManyStudentsStats\v1\Output;

use App\Controller\DTO\OutputDTOInterface;

readonly class OneCourseManyStudentsDTO implements OutputDTOInterface
{
    /**
     * @param StudentScoresDTO[] $studentScores
     */
    public function __construct(
        public int $courseId,
        public string $courseName,
        public float $avgScore,
        public int $maxScore,
        public string $period,
        public array $studentScores,
    ) {
    }
}
