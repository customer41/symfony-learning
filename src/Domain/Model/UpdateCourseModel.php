<?php

namespace App\Domain\Model;

use App\Domain\Enum\CourseStatus;
use Symfony\Component\Validator\Constraints as Assert;

readonly class UpdateCourseModel
{
    public function __construct(
        public string $title,
        public CourseStatus $status,
        public ?string $manager,
        #[Assert\LessThan(propertyPath: 'endDate', message: 'This value should be less than endDate')]
        public ?\DateTime $startDate,
        public ?\DateTime $endDate,
    ) {
    }
}
