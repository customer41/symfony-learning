<?php

namespace App\Domain\Model;

use App\Domain\Enum\TaskStatus;

readonly class UpdateStudentTaskModel
{
    public function __construct(
        public int $studentTaskId,
        public TaskStatus $taskStatus,
        public bool $taskMinRequirements,
    ) {
    }
}
