<?php

namespace App\Domain\Enum;

enum CourseStatus: string
{
    use BackedEnumTrait;

    case Planned = 'Планируется';
    case InProgress = 'Идёт обучение';
    case Completed = 'Завершён';
    case Cancelled = 'Отменён';
}
