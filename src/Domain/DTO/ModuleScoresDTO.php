<?php

namespace App\Domain\DTO;

readonly class ModuleScoresDTO implements EducationItemInterface
{
    public function __construct(
        public int $moduleId,
        public string $moduleName,
        public int $totalScore,
    ) {
    }
}
