<?php

namespace App\Domain\Enum;

enum Role: string
{
    use BackedEnumTrait;

    case User = 'ROLE_USER';
    case Admin = 'ROLE_ADMIN';
}
