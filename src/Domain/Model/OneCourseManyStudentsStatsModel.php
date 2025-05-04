<?php

namespace App\Domain\Model;

use App\Domain\DTO\StudentScoresDTO;

class OneCourseManyStudentsStatsModel
{
    public readonly int $maxScore;
    public readonly float $avgScore;

    /**
     * @param StudentScoresDTO[] $studentScores
     */
    public function __construct(
        public readonly int $courseId,
        public readonly string $courseName,
        public readonly \DatePeriod $period,
        public array $studentScores,
    ) {
        $this->maxScore = $this->calculateMaxScore();
        $this->avgScore = $this->calculateAvgScore();
    }

    public function sortByStudentScore(int $sortOrder): void
    {
        switch ($sortOrder) {
            case SORT_ASC:
                usort(
                    $this->studentScores,
                    static fn(StudentScoresDTO $a, StudentScoresDTO $b) => $a->totalScore <=> $b->totalScore
                );
                break;
            case SORT_DESC:
                usort(
                    $this->studentScores,
                    static fn(StudentScoresDTO $a, StudentScoresDTO $b) => $b->totalScore <=> $a->totalScore
                );
        }
    }

    private function calculateMaxScore(): int
    {
        return max($this->getScores());
    }

    private function calculateAvgScore(): float
    {
        $scores = $this->getScores();

        return array_sum($scores) / count($scores);
    }

    /**
     * @return int[]
     */
    private function getScores(): array
    {
        return array_map(static fn(StudentScoresDTO $studentScores) => $studentScores->totalScore, $this->studentScores);
    }
}
