<?php

namespace App\Controller\Web\CreateUser\v1;

use App\Controller\Web\CreateUser\v1\Input\CreateUserDTO;
use App\Controller\Web\CreateUser\v1\Output\CreatedStudentDTO;
use App\Controller\Web\CreateUser\v1\Output\CreatedUserDTO;
use App\Controller\Web\CreateUser\v1\Output\SubUserTypeOutputDTOInterface;
use App\Domain\Entity\Interfaces\SubUserTypeInterface;
use App\Domain\Entity\Student;
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

        if ($createUserDTO->createAs !== null) {
            $subUser = $this->createSubUser($createUserDTO, $user->getId());
        }

        return new CreatedUserDTO(
            $user->getId(),
            $user->getEmail(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->isActive(),
            $user->getRoles(),
            $user->getCreatedAt()->format('Y-m-d H:i:s'),
            $user->getUpdatedAt()->format('Y-m-d H:i:s'),
            isset($subUser) ? $this->createSubUserOutputDTO($subUser) : null,
        );
    }

    private function createSubUser(CreateUserDTO $createUserDTO, int $userId): SubUserTypeInterface
    {
        switch ($createUserDTO->createAs) {
            case UserType::Student->value:
                $createStudentModel = $this->modelFactory->makeModel(
                    CreateStudentModel::class,
                    $userId,
                    !empty($createUserDTO->birthDate) ? new \DateTime($createUserDTO->birthDate) : null,
                    $createUserDTO->age,
                    !empty($createUserDTO->gender) ? Gender::from($createUserDTO->gender) : null,
                    !empty($createUserDTO->phone) ? $createUserDTO->phone : null,
                );
                return $this->studentService->createStudent($createStudentModel);
            case UserType::Teacher->value:
                // TODO
            case UserType::Manager->value:
                // TODO
        }
    }

    private function createSubUserOutputDTO(SubUserTypeInterface $subUser): SubUserTypeOutputDTOInterface
    {
        if ($subUser instanceof Student) {
            return new CreatedStudentDTO(
                UserType::Student->value,
                $subUser->getId(),
                $subUser->getBirthDate()->format('Y-m-d'),
                $subUser->getAge(),
                $subUser->getGender()->value,
                '+7' . $subUser->getPhone(),
                $subUser->getCreatedAt()->format('Y-m-d H:i:s'),
                $subUser->getUpdatedAt()->format('Y-m-d H:i:s'),
            );
        } // TODO elseif ($subUser instanceof ...) {}
    }
}
