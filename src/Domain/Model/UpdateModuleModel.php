<?php

namespace App\Domain\Model;

use Symfony\Component\Validator\Constraints as Assert;

readonly class UpdateModuleModel
{
    public function __construct(
        public string $title,
        #[Assert\When(
            expression: "this.existsCourse === false",
            constraints: new Assert\IsNull(message: 'Course with this id does not exists'),
        )]
        public ?int $courseId,
        public bool $existsCourse,
    ) {
    }
}
