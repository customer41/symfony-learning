<?php

namespace App\Infrastructure\Repository;

use App\Domain\DTO\StudentCourseDTO;
use App\Domain\DTO\StudentsCourseDTO;
use App\Domain\Model\OneCourseManyStudentsStatsModel;
use App\Domain\Model\OneCourseOneStudentStatsModel;
use App\Domain\Repository\StudentStatsRepositoryCacheDecoratorInterface;
use App\Infrastructure\DTO\StatsPeriodDTO;
use App\Infrastructure\DTO\StudentCourseDTO as InfrastructureStudentCourseDTO;
use App\Infrastructure\Service\StudentStatsCacheService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class StudentStatsRepositoryCacheDecorator implements StudentStatsRepositoryCacheDecoratorInterface
{
    public function __construct(
        public readonly StudentStatsRepository $studentStatsRepository,
        public readonly StudentStatsCacheService $studentStatsCacheService,
        public readonly TagAwareCacheInterface $tagAwareCache,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getOneCourseManyStudentsAggregateStats(StudentsCourseDTO $studentsCourseDTO): OneCourseManyStudentsStatsModel
    {
        $statsPeriodDTO = new StatsPeriodDTO(
            $studentsCourseDTO->courseId,
            $studentsCourseDTO->period->getStartDate(),
            $studentsCourseDTO->period->getEndDate()
        );
        return $this->tagAwareCache->get(
            $this->studentStatsCacheService->getCacheKeyForStatsByCourseStudents($statsPeriodDTO),
            function(ItemInterface $item) use ($studentsCourseDTO, $statsPeriodDTO) {
                $oneCourseManyStudentsStatsModel = $this->studentStatsRepository->getOneCourseManyStudentsAggregateStats($studentsCourseDTO);
                $item->set($oneCourseManyStudentsStatsModel);
                $item->tag($this->studentStatsCacheService->getTagForStatsByCourseStudents($statsPeriodDTO));

                return $oneCourseManyStudentsStatsModel;
            }
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getOneCourseOneStudentAggregateStats(StudentCourseDTO $studentCourseDTO): OneCourseOneStudentStatsModel
    {
        $infrastructureStudentCourseDTO = new InfrastructureStudentCourseDTO(
            $studentCourseDTO->studentId,
            $studentCourseDTO->courseId,
            $studentCourseDTO->aggregateBy,
        );
        return $this->tagAwareCache->get(
            $this->studentStatsCacheService->getCacheKeyForStatsByCourseStudent($infrastructureStudentCourseDTO),
            function(ItemInterface $item) use ($studentCourseDTO, $infrastructureStudentCourseDTO) {
                $oneCourseOneStudentStatsModel = $this->studentStatsRepository->getOneCourseOneStudentAggregateStats($studentCourseDTO);
                $item->set($oneCourseOneStudentStatsModel);
                $item->tag($this->studentStatsCacheService->getTagForStatsByCourseStudent($infrastructureStudentCourseDTO));

                return $oneCourseOneStudentStatsModel;
            }
        );
    }
}
