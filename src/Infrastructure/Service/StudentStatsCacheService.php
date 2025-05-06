<?php

namespace App\Infrastructure\Service;

use App\Domain\Enum\EducationItem;
use App\Infrastructure\DTO\StatsPeriodDTO;
use App\Infrastructure\DTO\StudentCourseDTO;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class StudentStatsCacheService
{
    public const string CACHE_KEY_PREFIX = 'stats';

    public function __construct(
        public readonly TagAwareCacheInterface $tagAwareCache,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function invalidateCache(StudentCourseDTO $studentCourseDTO): bool
    {
        $tags = [];
        $tags[] = $this->getTagForStatsByCourseStudent($studentCourseDTO);
        $tags[] = $this->getTagForStatsByCourseStudents(
            new StatsPeriodDTO($studentCourseDTO->courseId, null, new \DateTime())
        );

        return $this->tagAwareCache->invalidateTags($tags);
    }

    public function getCacheKeyForStatsByCourseStudent(StudentCourseDTO $studentCourseDTO): string
    {
        return self::CACHE_KEY_PREFIX
            . '_' . EducationItem::Course->value . "_$studentCourseDTO->courseId"
            . '_' . EducationItem::Student->value . "_$studentCourseDTO->studentId"
            . '_' . "agg_{$studentCourseDTO->aggregateBy->value}";
    }

    public function getTagForStatsByCourseStudent(StudentCourseDTO $studentCourseDTO): string
    {
        return EducationItem::Course->value . "_$studentCourseDTO->courseId"
            . '_' . EducationItem::Student->value . "_$studentCourseDTO->studentId";
    }

    public function getCacheKeyForStatsByCourseStudents(StatsPeriodDTO $statsPeriodDTO): string
    {
        return self::CACHE_KEY_PREFIX
            . '_' . EducationItem::Course->value . "_$statsPeriodDTO->courseId"
            . '_' . $statsPeriodDTO->startStatsDate->format('Ymd')
            . '_' . $statsPeriodDTO->endStatsDate->format('Ymd');
    }

    public function getTagForStatsByCourseStudents(StatsPeriodDTO $statsPeriodDTO): string
    {
        return EducationItem::Course->value . "_{$statsPeriodDTO->courseId}_{$statsPeriodDTO->endStatsDate->format('Ymd')}";
    }
}
