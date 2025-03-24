<?php

namespace App\Controller\Web\CreateUser\v1\Output;

use App\Controller\DTO\OutputDTOInterface;

readonly class CreatedUserDTO implements OutputDTOInterface
{
    /** @param string[] $roles */
    public function __construct(
        public int $id,
        public string $email,
        public string $firstName,
        public string $lastName,
        public bool $isActive,
        public array $roles = [],
        public ?CreatedStudentDTO $student,
        public null $teacher, // TODO add CreatedTeacherDTO later
        public null $manager, // TODO add CreatedManagerDTO later
        public string $createdAt,
        public string $updatedAt,
    ) {
    }
}
