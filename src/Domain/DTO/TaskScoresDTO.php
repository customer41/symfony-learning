<?php

namespace App\Domain\DTO;

readonly class TaskScoresDTO implements EducationItemInterface
{
    public function __construct(
        public int $taskId,
        public string $taskName,
        public int $lessonId,
        public string $lessonName,
        public int $totalScore,
    ) {
    }
}
