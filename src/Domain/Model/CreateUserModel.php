<?php

namespace App\Domain\Model;

readonly class CreateUserModel
{
    /** @param string[] $roles */
    public function __construct(
        public string $email,
        public string $firstName,
        public string $lastName,
        public string $password,
        public bool $isActive,
        public array $roles = [],
    ) {
    }
}
