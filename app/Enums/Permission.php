<?php

namespace App\Enums;

use App\Traits\EnumValues;

enum Permission: string
{
    case Calendar = 'calendar';
    case AcademicStructure = 'academic';
    case StudentsManagement = 'students';
    case StudentActivities = 'activities';
    case NewsAnnouncement = 'news';
    case ResourcesCentre = 'resources';

    use EnumValues;
}
