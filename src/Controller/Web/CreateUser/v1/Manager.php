<?php

namespace App\Controller\Web\CreateUser\v1;

use App\Controller\Web\CreateUser\v1\Input\CreateUserDTO;
use App\Controller\Web\CreateUser\v1\Output\CreatedStudentDTO;
use App\Controller\Web\CreateUser\v1\Output\CreatedUserDTO;
use App\Domain\Enum\Gender;
use App\Domain\Enum\UserType;
use App\Domain\Factory\ModelFactory;
use App\Domain\Model\CreateStudentModel;
use App\Domain\Model\CreateUserModel;
use App\Domain\Service\StudentService;
use App\Domain\Service\UserService;

class Manager
{
    public function __construct(
        /** @var ModelFactory<CreateUserModel> */
        private readonly ModelFactory $modelFactory,
        private readonly UserService $userService,
        private readonly StudentService $studentService,
    ) {
    }

    public function create(CreateUserDTO $createUserDTO): CreatedUserDTO
    {
        $createUserModel = $this->modelFactory->makeModel(
            CreateUserModel::class,
            $createUserDTO->email,
            $createUserDTO->firstName,
            $createUserDTO->lastName,
            $createUserDTO->password,
            $createUserDTO->isActive,
            $createUserDTO->roles,
        );
        $user = $this->userService->createUser($createUserModel);

        switch ($createUserDTO->createAs) {
            case UserType::Student->value:
                $createStudentModel = $this->modelFactory->makeModel(
                    CreateStudentModel::class,
                    $user->getId(),
                    !empty($createUserDTO->birthDate) ? new \DateTime($createUserDTO->birthDate) : null,
                    $createUserDTO->age,
                    !empty($createUserDTO->gender) ? Gender::from($createUserDTO->gender) : null,
                    !empty($createUserDTO->phone) ? $createUserDTO->phone : null,
                );
                $student = $this->studentService->createStudent($createStudentModel);
                $createdStudentDTO = new CreatedStudentDTO(
                    $student->getId(),
                    $student->getBirthDate()->format('Y-m-d'),
                    $student->getAge(),
                    $student->getGender()->value,
                    '+7' . $student->getPhone(),
                    $student->getCreatedAt()->format('Y-m-d H:i:s'),
                    $student->getUpdatedAt()->format('Y-m-d H:i:s'),
                );
                break;
            case UserType::Teacher->value:
                // TODO
                break;
            case UserType::Manager->value;
                // TODO
        }

        return new CreatedUserDTO(
            $user->getId(),
            $user->getEmail(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->isActive(),
            $user->getRoles(),
            $createdStudentDTO ?? null,
            null, // TODO add createdTeacherDTO later
            null, // TODO add createdManagerDTO later
            $user->getCreatedAt()->format('Y-m-d H:i:s'),
            $user->getUpdatedAt()->format('Y-m-d H:i:s'),
        );
    }
}
