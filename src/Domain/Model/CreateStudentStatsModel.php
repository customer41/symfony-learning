<?php

namespace App\Domain\Model;

use App\Domain\Entity\Skill;

readonly class CreateStudentStatsModel
{
    public const int MIN_TASK_PERCENT_STEP = 10;

    public function __construct(
        public int $studentId,
        public array $skillIds,
    ) {
    }

    public function getScoreBySkill(Skill $skill): int
    {
        return intdiv($skill->getTaskPercent(), self::MIN_TASK_PERCENT_STEP);
    }
}
