<?php

namespace App\Controller\Web\CreateUser\v1\Input;

use App\Domain\Enum\Gender;
use App\Domain\Enum\Role;
use App\Domain\Enum\UserType;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateUserDTO
{
    /** @param string[] $roles */
    public function __construct(
        #[Assert\Email()]
        public string $email,
        #[Assert\Length(min: 2, max: 20)]
        public string $firstName,
        #[Assert\Length(min: 2, max: 20)]
        public string $lastName,
        #[Assert\Length(min: 5, max: 10)]
        public string $password,
        public bool $isActive,
        #[Assert\All([new Assert\Choice(callback: [Role::class, 'values'])])]
        public array $roles = [],
        #[Assert\Choice(callback: [UserType::class, 'values'])]
        public ?string $createAs = null,
        #[Assert\Date()]
        public ?string $birthDate = null,
        #[Assert\Range(min: 18, max: 75)]
        public ?int $age = null,
        #[Assert\Choice(callback: [Gender::class, 'values'])]
        public ?string $gender = null,
        #[Assert\Regex('~^\d{10}$~')]
        public ?string $phone = null,
    ) {
    }
}
