<?php

namespace App\Controller\Web\GetOneCourseOneStudentStats\v1\Output;

readonly class TaskScoresDTO implements EducationItemOutputDTOInterface
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
