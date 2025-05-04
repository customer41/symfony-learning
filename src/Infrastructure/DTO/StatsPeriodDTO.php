<?php

namespace App\Infrastructure\DTO;

readonly class StatsPeriodDTO
{
    public function __construct(
        public int $courseId,
        public ?\DateTimeInterface $startStatsDate,
        public ?\DateTimeInterface $endStatsDate,
    ) {
    }
}
