<?php

namespace App\Controller\Web\GetOneCourseOneStudentStats\v1\Output;

readonly class ModuleScoresDTO implements EducationItemOutputDTOInterface
{
    public function __construct(
        public int $moduleId,
        public string $moduleName,
        public int $totalScore,
    ) {
    }
}
