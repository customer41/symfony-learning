<?php

namespace App\Controller\Web\GetOneCourseManyStudentsStats\v1\Input;

use Symfony\Component\Validator\Constraints as Assert;

readonly class StudentsCourseDTO
{
    public function __construct(
        #[Assert\Choice(choices: [SORT_ASC, SORT_DESC])]
        public int $sortOrder,
        #[Assert\Range(maxPropertyPath: 'endDate')]
        public \DateTime $startDate,
        #[Assert\Range(max: 'today')]
        public \DateTime $endDate,
    ) {
    }
}
