<?php

namespace App\Domain\DTO;

readonly class StudentScoresDTO
{
    public function __construct(
        public int $studentId,
        public string $studentName,
        public int $totalScore,
    ) {
    }
}
