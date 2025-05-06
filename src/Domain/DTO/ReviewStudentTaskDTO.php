<?php

namespace App\Domain\DTO;

use App\Domain\Enum\TaskStatus;

readonly class ReviewStudentTaskDTO
{
    /**
     * @param int[] $skillIds
     */
    public function __construct(
        public TaskStatus $taskStatus,
        public array $skillIds,
    ) {
    }
}
