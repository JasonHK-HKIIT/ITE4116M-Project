<?php

namespace App\Enums;

use App\Traits\EnumValues;

enum CalendarEventType: string
{
    case CLASS_TYPE = 'class'; // Laravel can't use 'class' as enum case
    case ACTIVITY = 'activity';
    case INSTITUTE_HOLIDAY = 'institute_holiday';
    case PUBLIC_HOLIDAY = 'public_holiday';

    use EnumValues;
}
