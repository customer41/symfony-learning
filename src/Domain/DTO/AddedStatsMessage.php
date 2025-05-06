<?php

namespace App\Domain\DTO;

readonly class AddedStatsMessage
{
    public function __construct(
        public int $studentId,
        public int $courseId,
    ) {
    }
}
