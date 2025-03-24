<?php

namespace App\Domain\Model;

use App\Domain\Enum\Gender;

readonly class CreateStudentModel
{
    public ?int $age;

    public function __construct(
        public int $userId,
        public ?\DateTime $birthDate = null,
        ?int $age = null,
        public ?Gender $gender = null,
        public ?string $phone = null,
    ) {
        $this->setAge($age);
    }

    private function setAge(?int $age): void
    {
        if ($age !== null) {
            $this->age = $age;
            return;
        }

        if ($this->birthDate instanceof \DateTime) {
            $this->age = (new \DateTime())->diff($this->birthDate)->y;
        }
    }
}
