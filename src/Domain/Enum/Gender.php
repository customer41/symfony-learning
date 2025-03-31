<?php

namespace App\Domain\Enum;

enum Gender: string
{
    use BackedEnumTrait;

    case Male = 'Мужской';
    case Female = 'Женский';
}
