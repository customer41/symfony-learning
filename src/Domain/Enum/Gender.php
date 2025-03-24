<?php

namespace App\Domain\Enum;

enum Gender: string
{
    use BackedEnumTrait;

    case Male = 'Mужской';
    case Female = 'Женский';
}
