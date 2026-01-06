<?php

namespace App\Enums;

use App\Traits\EnumValues;

enum NewsArticleStatus: string
{
    case Draft = 'draft';
    case Published = 'published';

    use EnumValues;
}
