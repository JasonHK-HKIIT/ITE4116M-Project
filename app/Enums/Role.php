<?php

namespace App\Enums;

use App\Traits\EnumValues;

enum Role: string
{
    case STUDENT = 'student';
    case ADMIN = 'admin';

    use EnumValues;
}
