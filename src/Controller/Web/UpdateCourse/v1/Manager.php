<?php

namespace App\Controller\Web\UpdateCourse\v1;

use App\Controller\Web\UpdateCourse\v1\Input\UpdateCourseDTO;
use App\Controller\Web\UpdateCourse\v1\Output\UpdatedCourseDTO;
use App\Domain\Enum\CourseStatus;
use App\Domain\Factory\ModelFactory;
use App\Domain\Model\UpdateCourseModel;
use App\Domain\Service\CourseService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Manager
{
    public function __construct(
        /** @var ModelFactory<UpdateCourseModel> */
        private readonly ModelFactory $modelFactory,
        private readonly CourseService $courseService,
    ) {
    }

    public function update(UpdateCourseDTO $updateCourseDTO, int $courseId): UpdatedCourseDTO
    {
        $updateCourseModel = $this->modelFactory->makeModel(
            UpdateCourseModel::class,
            $updateCourseDTO->title,
            CourseStatus::from($updateCourseDTO->status),
            !empty($updateCourseDTO->manager) ? $updateCourseDTO->manager: null,
            !empty($updateCourseDTO->startDate) ? new \DateTime($updateCourseDTO->startDate) : null,
            !empty($updateCourseDTO->endDate) ? new \DateTime($updateCourseDTO->endDate) : null,
        );

        try {
            $course = $this->courseService->updateCourse($updateCourseModel, $courseId);
        } catch (EntityNotFoundException) {
            throw new NotFoundHttpException('Course not found');
        }

        return new UpdatedCourseDTO(
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
