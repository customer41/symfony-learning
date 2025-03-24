<?php

namespace App\Domain\Enum;

enum UserType: string
{
    use BackedEnumTrait;

    case Student = 'Student';
    case Teacher = 'Teacher';
    case Manager = 'Manager';
}
