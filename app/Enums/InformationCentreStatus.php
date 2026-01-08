<?php

namespace App\Enums;

use App\Traits\EnumValues;

enum InformationCentreStatus: string
{
    case Draft = 'draft';
    case Published = 'published';

    use EnumValues;
}
