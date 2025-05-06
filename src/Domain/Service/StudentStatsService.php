<?php

namespace App\Domain\Service;

use App\Domain\Bus\InvalidateCacheStatsBusInterface;
use App\Domain\DTO\AddedStatsMessage;
use App\Domain\DTO\StudentCourseDTO;
use App\Domain\DTO\StudentsCourseDTO;
use App\Domain\Entity\Student;
use App\Domain\Entity\StudentStats;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\CreateStudentStatsModel;
use App\Domain\Model\OneCourseManyStudentsStatsModel;
use App\Domain\Model\OneCourseOneStudentStatsModel;
use App\Domain\Repository\StudentStatsRepositoryCacheDecoratorInterface;
use App\Domain\Repository\StudentStatsRepositoryInterface;

class StudentStatsService
{
    public function __construct(
        private readonly StudentStatsRepositoryInterface $studentStatsRepository,
        private readonly StudentStatsRepositoryCacheDecoratorInterface $studentStatsRepositoryCacheDecorator,
        private readonly InvalidateCacheStatsBusInterface $invalidateCacheStatsBus,
        private readonly SkillService $skillService,
        private readonly StudentService $studentService,
    ) {
    }

    /**
     * @note: Each parameter is identifier(s) of related entities with stats
     *
     * @return StudentStats[]
     */
    public function getStudentStatsRaw(
        int|array|null $student,
        int|array|null $course = null,
        int|array|null $module = null,
        int|array|null $lesson = null,
        int|array|null $task = null,
        int|array|null $skill = null,
    ): array {
        return $this->studentStatsRepository->findByIds($student, $course, $module, $lesson, $task, $skill);
    }

    public function getOneCourseManyStudentsStats(StudentsCourseDTO $studentsCourseDTO): OneCourseManyStudentsStatsModel
    {
        $oneCourseManyStudentsStatsModel = $this->studentStatsRepositoryCacheDecorator->getOneCourseManyStudentsAggregateStats($studentsCourseDTO);
        $oneCourseManyStudentsStatsModel->sortByStudentScore($studentsCourseDTO->sortOrder);

        return $oneCourseManyStudentsStatsModel;
    }

    public function getOneCourseOneStudentStats(StudentCourseDTO $studentCourseDTO): OneCourseOneStudentStatsModel
    {
        return $this->studentStatsRepositoryCacheDecorator->getOneCourseOneStudentAggregateStats($studentCourseDTO);
    }

    /**
     * @param CreateStudentStatsModel $createStatsModel
     *
     * @throws EntityNotFoundException
     */
    public function addStudentStats(CreateStudentStatsModel $createStatsModel): void
    {
        $student = $this->studentService->findStudentById($createStatsModel->studentId);
        if ($student === null) {
            throw new EntityNotFoundException(Student::class);
        }

        $studentStats = [];
        $skills = $this->skillService->findSkillByIds($createStatsModel->skillIds);
        foreach ($skills as $skill) {
            $studentStats[] = new StudentStats(
                $student,
                $skill->getTask()->getLesson()->getModule()->getCourse(),
                $skill->getTask()->getLesson()->getModule(),
                $skill->getTask()->getLesson(),
                $skill->getTask(),
                $skill,
                $createStatsModel->getScoreBySkill($skill),
            );
        }

        $this->studentStatsRepository->createBatch($studentStats);

        $this->invalidateCacheStatsBus->sendAddedStatsMessageAsync(
            new AddedStatsMessage(
                $student->getId(),
                $skills[0]->getTask()->getLesson()->getModule()->getCourse()->getId(),
            ),
        );
    }
}
