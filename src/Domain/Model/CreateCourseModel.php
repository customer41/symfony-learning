<?php

namespace App\Domain\Model;

use App\Domain\Enum\CourseStatus;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateCourseModel
{
    public function __construct(
        public string $title,
        public CourseStatus $status,
        public ?string $manager,
        #[Assert\GreaterThan('+1 months')]
        public ?\DateTime $startDate,
        #[Assert\GreaterThan('+2 months')]
        public ?\DateTime $endDate,
    ) {
    }
}
