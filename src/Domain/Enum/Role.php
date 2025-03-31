<?php

namespace App\Domain\Enum;

enum Role: string
{
    use BackedEnumTrait;

    // Base roles
    case User = 'ROLE_USER';
    case Admin = 'ROLE_ADMIN';

    // Manager roles
    case ManagerCRUDEduEntity = 'ROLE_MANAGER_CRUD_EDU_ENTITY';

    // Student roles
    case StudentViewSelfEdu = 'ROLE_STUDENT_VIEW_SELF_EDU';
}
