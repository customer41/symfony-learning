<?php

namespace App\Infrastructure\Bus;

enum AmqpExchangeEnum: string
{
    case AddStudentStats = 'add_student_stats';
}
