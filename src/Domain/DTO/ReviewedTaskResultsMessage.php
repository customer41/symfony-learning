<?php

namespace App\Domain\DTO;

readonly class ReviewedTaskResultsMessage
{
    /**
     * @param int[] $skillIds
     */
    public function __construct(
        public int $studentId,
        public array $skillIds,
        public int $studentTaskId,
        public string $taskStatus,
        public bool $taskMinRequirements,
    ) {
    }
}
