<?php

namespace App\Controller\Web\CreateCourse\v1\Input;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateCourseDTO
{
    public function __construct(
        #[Assert\Length(min: 10, max: 255)]
        public string $title,
        #[Assert\Length(max: 255)]
        public ?string $manager,
        #[Assert\Date()]
        public ?string $startDate,
        #[Assert\Date()]
        public ?string $endDate,
    ) {
    }
}
