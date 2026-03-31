<?php

namespace App\Enums\Activity;

use App\Traits\EnumValues;
use App\Traits\TranslatableEnum;

enum Disciplines: string
{
    case IT = 'IT';
    case Business = 'Business';
    case Engineering = 'Engineering';
    case Arts = 'Arts';

    use EnumValues;
    use TranslatableEnum;
}
