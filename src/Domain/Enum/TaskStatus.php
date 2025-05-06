<?php

namespace App\Domain\Enum;

enum TaskStatus: string
{
    use BackedEnumTrait;

    case New = 'Новая';
    case Accepted = 'Принято';
    case ForReview = 'На ревью';
    case ForRevision = 'На доработку';
}
