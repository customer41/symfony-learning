<?php

namespace App\Controller\Web\GetOneCourseOneStudentStats\v1\Input;

use App\Domain\Enum\EducationItem;
use Symfony\Component\Validator\Constraints as Assert;

readonly class StudentCourseDTO
{
    public function __construct(
        public int $courseId,
        public int $studentId,
        #[Assert\Choice(choices: [
            EducationItem::Module->value,
            EducationItem::Task->value
        ])]
        public string $aggregateBy,
    ) {
    }
}
