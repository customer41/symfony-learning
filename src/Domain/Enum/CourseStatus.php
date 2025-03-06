<?php

namespace App\Domain\Enum;

enum CourseStatus: string
{
    case Planned = 'Планируется';
    case InProgress = 'Идёт обучение';
    case Completed = 'Завершён';
}
