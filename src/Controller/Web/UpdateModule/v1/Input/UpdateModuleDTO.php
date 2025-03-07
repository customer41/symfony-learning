<?php

namespace App\Controller\Web\UpdateModule\v1\Input;

use Symfony\Component\Validator\Constraints as Assert;

readonly class UpdateModuleDTO
{
    public function __construct(
        #[Assert\Length(min: 10, max: 255)]
        public string $title,
        public ?int $courseId,
    ) {
    }
}
