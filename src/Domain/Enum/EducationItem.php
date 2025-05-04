<?php

namespace App\Domain\Enum;

enum EducationItem: string
{
    use BackedEnumTrait;

    case Student = 'student';
    case Course = 'course';
    case Module = 'module';
    case Lesson = 'lesson';
    case Task = 'task';
    case Skill = 'skill';
}
