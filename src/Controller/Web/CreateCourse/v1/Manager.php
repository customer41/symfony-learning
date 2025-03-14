<?php

namespace App\Controller\Web\CreateCourse\v1;

use App\Controller\Web\CreateCourse\v1\Input\CreateCourseDTO;
use App\Controller\Web\CreateCourse\v1\Output\CreatedCourseDTO;
use App\Domain\Enum\CourseStatus;
use App\Domain\Factory\ModelFactory;
use App\Domain\Model\CreateCourseModel;
use App\Domain\Service\CourseService;

class Manager
{
    public function __construct(
        /** @var ModelFactory<CreateCourseModel> */
        private readonly ModelFactory $modelFactory,
        private readonly CourseService $courseService,
    ) {
    }

    public function create(CreateCourseDTO $createCourseDTO): CreatedCourseDTO
    {
        $createCourseModel = $this->modelFactory->makeModel(
            CreateCourseModel::class,
            $createCourseDTO->title,
            CourseStatus::Planned,
            !empty($createCourseDTO->manager) ? $createCourseDTO->manager: null,
            !empty($createCourseDTO->startDate) ? new \DateTime($createCourseDTO->startDate) : null,
            !empty($createCourseDTO->endDate) ? new \DateTime($createCourseDTO->endDate) : null,
        );
        $course = $this->courseService->createCourse($createCourseModel);

        return new CreatedCourseDTO(
            $course->getId(),
            $course->getTitle(),
            $course->getStatus()->value,
            $course->getManager(),
            $course->getStartDate()?->format('Y-m-d'),
            $course->getEndDate()?->format('Y-m-d'),
            $course->getCreatedAt()->format('Y-m-d H:i:s'),
            $course->getUpdatedAt()->format('Y-m-d H:i:s'),
        );
    }
}
