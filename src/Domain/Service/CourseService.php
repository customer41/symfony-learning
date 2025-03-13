<?php

namespace App\Domain\Service;

use App\Domain\Entity\Course;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\CreateCourseModel;
use App\Domain\Model\UpdateCourseModel;
use App\Infrastructure\Repository\CourseRepository;

class CourseService
{
    public function __construct(
        private readonly CourseRepository $courseRepository,
    ) {
    }

    public function createCourse(CreateCourseModel $createCourseModel): Course
    {
        $course = new Course();
        $course->setTitle($createCourseModel->title);
        $course->setStatus($createCourseModel->status);
        $course->setManager($createCourseModel->manager);
        $course->setStartDate($createCourseModel->startDate);
        $course->setEndDate($createCourseModel->endDate);
        $this->courseRepository->create($course);

        return $course;
    }

    public function getCourseById(int $id): Course
    {
        $course = $this->courseRepository->findById($id);

        if ($course === null) {
            throw new EntityNotFoundException(Course::class);
        }

        return $course;
    }

    public function isExistsCourseById(int $id): bool
    {
        try {
            $this->getCourseById($id);
        } catch (EntityNotFoundException) {
            return false;
        }

        return true;
    }

    public function updateCourse(UpdateCourseModel $updateCourseModel, int $courseId): Course
    {
        $course = $this->courseRepository->findById($courseId);

        if ($course === null) {
            throw new EntityNotFoundException(Course::class);
        }

        $course->setTitle($updateCourseModel->title);
        $course->setStatus($updateCourseModel->status);
        $course->setManager($updateCourseModel->manager);
        $course->setStartDate($updateCourseModel->startDate);
        $course->setEndDate($updateCourseModel->endDate);
        $this->courseRepository->update();

        return $course;
    }

    public function deleteCourseById(int $id): void
    {
        $course = $this->courseRepository->findById($id);

        if ($course === null) {
            throw new EntityNotFoundException(Course::class);
        }

        $this->courseRepository->remove($course);
    }
}
