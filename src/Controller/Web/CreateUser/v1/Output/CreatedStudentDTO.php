<?php

namespace App\Controller\Web\CreateUser\v1\Output;

readonly class CreatedStudentDTO implements SubUserTypeOutputDTOInterface
{
    public function __construct(
        public string $userType,
        public int $id,
        public ?string $birthDate = null,
        public ?int $age = null,
        public ?string $gender = null,
        public ?string $phone = null,
        public string $createdAt,
        public string $updatedAt,
    ) {
    }
}
