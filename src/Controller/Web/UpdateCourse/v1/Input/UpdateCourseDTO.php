<?php

namespace App\Controller\Web\UpdateCourse\v1\Input;

use App\Domain\Enum\CourseStatus;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateCourseDTO
{
    public function __construct(
        #[Assert\Length(min: 10, max: 255)]
        public string $title,
        #[Assert\Choice(callback: [CourseStatus::class, 'values'])]
        public string $status,
        #[Assert\Length(max: 255)]
        public ?string $manager,
        #[Assert\Date()]
        public ?string $startDate,
        #[Assert\Date()]
        public ?string $endDate,
    ) {
    }
}
